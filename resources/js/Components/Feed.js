import React from "react";

const Feed = ({ feed }) => {
    return (
        <div className="flex items-center mb-4">
            <div className="mr-2">
                <img src="https://www.google.com/s2/favicons?domain=http://smashingmagazine.com" />
            </div>
            {feed.source}
        </div>
    );
};

export default Feed;
