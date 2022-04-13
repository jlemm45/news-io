import { truncate } from 'lodash';
import React from 'react';

const Feed = ({ feed }) => {
  return (
    <div className="flex items-center mb-4">
      <div className="mr-2 w-5">
        <img src={feed.favicon} className="w-full" />
      </div>
      {truncate(feed.title, { length: 30 })}
    </div>
  );
};

export default Feed;
