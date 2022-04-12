import React from "react";

const Feed = ({ feed }) => {
    return (
        <div className="flex items-center mb-4">
            <div className="mr-2">
                <img src={feed.favicon} />
            </div>
            {feed.title}
        </div>
    );
};

export default Feed;
