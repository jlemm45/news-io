'use strict';

(function(angular) {
    var snugfeeds = angular.module('snug-feeds', ['article', 'snugfeed.service.articles', 'ngCookies', 'modal', 'logincomponent', 'registercomponent', 'snugfeed.service.user', 'managefeedscomponent', 'snugfeed.service.feeds', 'newfeedcomponent', 'readarticlecomponent', 'snugfeed.service.preference']);

    /**
     * Feeds Controller
     */
    snugfeeds.controller('feedsController', function($scope,$http,snugfeedArticlesService,$cookies,snugfeedUserService,snugfeedFeedsService,$timeout,preferenceService) {

        $scope.feeds = [];                                          //all active articles
        $scope.lastFeedID = 43;                                     //last article id for loading more articles
        $scope.sidebarToggle = preferenceService.get('sidebar');    //toggles state of sidebar
        $scope.articleFilter = false;                               //toggles filtering articles
        $scope.loginModal = {                                       //custom options for login modal
            activeModel: 'login'                                    //login or register
        };
        $scope.manageFeedsModal = {                                 //custom options for manage feeds modal
            saveFeeds: function(feeds) {                            //save the selected feeds
                handleFeedUpdate(feeds);
            }
        };
        $scope.user = false;                                        //holds active user info
        $scope.activeFeeds = $cookies.getObject('feeds');           //active feeds
        $scope.savedArticles = {};                                  //user saved articles
        $scope.showSaved = false;                                   //if we are showing saved articles
        $scope.articleView = preferenceService.get('articleView');  //handles toggling view to list or grid
        $scope.articleToRead = {};                                  //active article to read in modal
        $scope.noti = {                                             //holds values for top notification bar
            text: 'Alert'
        };
        var msnry = false;
        function resetLayout() {
            if(!$scope.articleView) {
                $timeout(function () {
                    msnry = new Masonry( '#article-contain', {      //init mason layout
                        itemSelector: '.mason',
                        columnWidth: '.mason-sizer',
                        percentPosition: true,
                        gutter: '.mason-gutter'
                    });

                    imagesLoaded($('#article-contain')).on( 'progress', function() {
                        msnry.layout();
                    });
                }, 200);
            }
            else {
                if(msnry) msnry.destroy();
            }
        }

        /**
         * Get's all articles saved by the user
         */
        function getSavedArticles() {
            snugfeedArticlesService.getSavedArticles().then(function(resp) {
                $scope.savedArticles = resp.data;
            });
        }

        /**
         * Returns feed id's from cookie
         * @returns {*|Array}
         */
        function getFeedsIds() {
            var feeds = $scope.user.feeds ? $scope.user.feeds : $scope.activeFeeds;
            return feeds.map(function(i) {
                return i.id;
            });
        }

        //function updatePreferencesCookie(preference, value) {
        //    var cookie = $cookies.getObject('preferences') ? $cookies.getObject('preferences') : {};
        //    cookie[preference] = value;
        //    $cookies.putObject('preferences', cookie);
        //}
        //
        //function getPreference(preference) {
        //    return $cookies.getObject('preferences')[preference] == true;
        //}

        /**
         * Subscribe logged in user to sockets
         */
        function subscribeToSockets() {
            $http.get('/api/socket').then(function(resp) {
                var socket = io.connect(resp.data, {
                    reconnection: false
                });

                socket.on('article', function(json) {
                    handleNewArticles(json);
                });

                socket.emit('subscribe', {
                    userID: $scope.user.id,
                    feeds: getFeedsIds()
                });
            });
        }

        /**
         * Get's current user status as well as selected feeds
         */
        function getUserStatus() {
            snugfeedUserService.getUserStatus().then(function(data) {
                if(data.data.user) {
                    $scope.user = data.data.user;
                    $scope.activeFeeds = $scope.user.feeds;
                    $scope.user.initials = snug.generateAvatarInitials($scope.user.name);
                    subscribeToSockets();
                }
                $scope.getArticles(false);
            });
        }

        /**
         * Update user active feeds via feed service
         * @param feeds
         */
        function handleFeedUpdate(feeds) {
            feeds = feeds.filter(function(i) {
                if(i.active) return i;
            });

            snugfeedFeedsService.updateFeeds(feeds).then(function(data) {
                getUserStatus();
                $('#feedsModal').modal('hide');
            });
        }

        /**
         * Get articles based on selected articles in cookie
         * @param page
         */
        $scope.getArticles = function(page) {
            if(!page) $scope.feeds = [];
            page = page ? $scope.lastFeedID : false;
            var ids = $scope.articleFilter ? [$scope.articleFilter] : getFeedsIds();
            snugfeedArticlesService.getArticles(page,ids).then(function(data) {
                $scope.feeds = $scope.feeds.concat(data.data);
                $scope.lastFeedID = data.data[data.data.length - 1].id;

                resetLayout();

            });
        };

        /**
         * Toggles sidebar state
         */
        $scope.toggleSidebar = function() {
            $scope.sidebarToggle = $scope.sidebarToggle ? false : true;
            preferenceService.set('sidebar', $scope.sidebarToggle);
        };

        /**
         * Filters articles
         * @param id
         */
        $scope.filterArticles = function(id) {
            $scope.articleFilter = id;
            $scope.getArticles(false);
            $scope.showSaved = false;
        };

        /**
         * Shows the login modal
         */
        $scope.showLoginModal = function() {
            $('#loginModal').modal('show');
            $scope.loginModal.activeModel = 'login';
        };

        /**
         * Show the add new feed modal
         */
        $scope.showNewFeedModal = function() {
            $('#newFeedModal').modal('show');
        };

        /**
         * Shows the manage feed modal
         */
        $scope.showMangeFeedsModal = function() {
            $('#feedsModal').modal('show');
        };

        /**
         * Change view to saved articles only
         */
        $scope.showSavedArticles = function() {
            $scope.feeds = $scope.savedArticles;
            $scope.showSaved = true;
            resetLayout();
        };

        //on load
        getUserStatus();
        getSavedArticles();

        function incoming(text) {
            $scope.incoming = true;
            $scope.noti.text = text;
            setTimeout(function() {
                $scope.incoming = false;
                $scope.$apply();
            },3000);
            $scope.$apply();
        }

        /**
         * Handle new incoming articles. Only use articles in which feeds users are listening to.
         * @param json
         */
        function handleNewArticles(json) {
            var userFeedIds = getFeedsIds();
            var idsToGet = [];
            _.each(json[0], function(articleArr, feedID) {
                if(userFeedIds.indexOf(parseInt(feedID)) > -1) {
                    _.each(articleArr, function(articleID, k) {
                        idsToGet.push(articleID);
                        $scope.feeds.unshift({incoming: true});
                    });
                }
                resetLayout();
            });
            if(idsToGet.length > 0) {
                incoming('Incoming Article');
                snugfeedArticlesService.getArticlesByIds(idsToGet.join()).then(function(resp) {
                    var length = resp.data.length - 1;
                    _.each(resp.data, function(v, k) {
                        bringInNewArticle(length, v);
                        length--;
                    });
                });
            }
        }

        function bringInNewArticle(index, article) {
            $timeout(function() {
                console.log(index);
                $scope.feeds[index] = article;
                resetLayout();
            },3000*(index+1));
        }

        $scope.toggleView = function(toggle) {
            $scope.articleView = toggle;
            resetLayout();
            preferenceService.set('articleView', toggle);
        };

        //listen to emit event from article saved
        $scope.$on('article saved', function(c,v,r) {
            $scope.savedArticles.push(v);
            incoming('Article Saved!');
        });

        $scope.$on('login success', function() {
            getUserStatus();
            $('#loginModal').modal('hide');
        });

        $scope.$on('register success', function() {
            $scope.loginModal.activeModel = 'login';
        });

        $scope.$on('read article', function(c,value,r) {
            $scope.articleToRead = value;
            $('#readArticleModal')
                .modal({ blurring: true }).modal('show');
        });

        $scope.$on('add feed success', function() {
            incoming('New Feed Added. Add the new feed in manage feeds!');
            $('#newFeedModal').modal('hide');
            $scope.$broadcast('reload manage feeds');
        });
    });
})(angular);