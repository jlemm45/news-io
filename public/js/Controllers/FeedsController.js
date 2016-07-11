'use strict';

(function(angular) {
    var snugfeeds = angular.module('snug-feeds', ['article', 'snugfeed.service.articles', 'ngCookies', 'modal', 'logincomponent', 'registercomponent', 'snugfeed.service.user', 'managefeedscomponent', 'snugfeed.service.feeds', 'newfeedcomponent', 'readarticlecomponent', 'snugfeed.service.preference', 'ngAnimate', 'toggleviewcomponent']);

    /**
     * Feeds Controller
     */
    snugfeeds.controller('feedsController', function($scope,$http,snugfeedArticlesService,$cookies,snugfeedUserService,snugfeedFeedsService,$timeout,preferenceService,$window) {

        $scope.loading = true;                                      //page loading control
        $scope.feeds = [];                                          //all active articles
        $scope.lastFeedID = 43;                                     //last article id for loading more articles
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
        $scope.showSettingsMenu = false;                            //toggles the settings menu bottom right
        $scope.showNotice = preferenceService.get('hideNotice');    //shows the save feed top notice
        $scope.emptyFeeds = false;                                  //if we don't have any feeds
        $scope.noti = {                                             //holds values for top notification bar
            text: 'Alert'
        };
        $scope.showMobileFeeds = false;                             //toggle the feeds menu on mobile

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
                    getSavedArticles();
                }
                $scope.getArticles(false);
            });
        }

        /**
         * Update user active feeds via feed service
         * @param feeds
         */
        function handleFeedUpdate(feeds) {
            snugfeedFeedsService.updateFeeds(feeds).then(function(data) {
                getUserStatus();
                $('#feedsModal').modal('hide');
                $scope.$broadcast('reload feeds');
            });
        }

        /**
         * Get articles based on selected articles in cookie
         * @param page
         */
        $scope.getArticles = function(page) {
            if(!page) {
                $scope.feeds = [];
                $window.scrollTo(0, 0);
            }
            page = page ? $scope.lastFeedID : false;
            var ids = $scope.articleFilter ? [$scope.articleFilter] : getFeedsIds();

            //if they have saved feeds
            if(ids.length > 0) {
                snugfeedArticlesService.getArticles(page,ids).then(function(data) {
                    if(data.data.length > 0) {
                        $scope.feeds = $scope.feeds.concat(data.data);
                        $scope.lastFeedID = data.data[data.data.length - 1].id;
                        resetLayout();
                    }
                    $scope.loading = false;
                    $scope.emptyFeeds = false;
                });
            }
            else {
                $scope.emptyFeeds = true;
                $scope.loading = false;
            }
        };

        /**
         * Filters articles
         * @param id
         */
        $scope.filterArticles = function(id) {
            $scope.articleFilter = id;
            $scope.getArticles(false);
            $scope.showSaved = false;
            $scope.showMobileFeeds = false;
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

        $scope.clearClick = function() {
            $scope.showSettingsMenu = false;
        };

        /**
         * Toggles the bottom right settings menu
         */
        $scope.toggleSettingsMenu = function($event) {
            if($event) $event.stopPropagation();
            $scope.showSettingsMenu = $scope.showSettingsMenu ? false : true;
        };

        /**
         * Toggles the feeds menu on mobile
         */
        $scope.toggleFeedsMobile = function() {
            $scope.showMobileFeeds = $scope.showMobileFeeds ? false : true;
        };

        //on load
        getUserStatus();

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
                    _.each(articleArr, function(articleID) {
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
                    _.each(resp.data, function(v) {
                        bringInNewArticle(length, v);
                        length--;
                    });
                });
            }
        }

        function bringInNewArticle(index, article) {
            $timeout(function() {
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
        $scope.$on('article saved', function(c,v) {
            $scope.savedArticles.push(v);
            incoming('Article Saved!');
        });

        $scope.$on('article deleted', function(c,article) {
            var arr = $scope.savedArticles;
            arr = _.filter(arr, function(item) {
                return item.id !== article.id;
            });
            $scope.savedArticles = arr;
            $scope.showSavedArticles();

            incoming('Article Deleted!');
        });

        $scope.$on('login success', function() {
            getUserStatus();
            $scope.$broadcast('reload manage feeds');
            $('#loginModal').modal('hide');
        });

        $scope.$on('register success', function() {
            $scope.loginModal.activeModel = 'login';
        });

        $scope.$on('read article', function(c,value) {
            $scope.articleToRead = value;
            $timeout(function() {
                $('#readArticleModal').modal('show');
            },100);
        });

        $scope.hideNotice = function() {
            $scope.showNotice = true;
            preferenceService.set('hideNotice', true);
        };

        $scope.$on('add feed success', function() {
            incoming('New Feed Added. We are working on adding the latest articles now!');
            $('#newFeedModal').modal('hide');
            getUserStatus();
            $scope.$broadcast('reload manage feeds');
        });
    }).directive('topScroll', function($anchorScroll,$window) {
        return {
            link: function(scope, element, attrs) {
                $($window).on('scroll', function () {
                    if($window.scrollY > 75 && window.innerWidth > 723) {
                        $(element).css({'top': '-75px'});
                    }
                    else {
                        $(element).css({'top': 0});
                    }
                });
            },
            restrict: 'A'
        }
    })
})(angular);