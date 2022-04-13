import React from 'react';
import Authenticated from '@/Layouts/Authenticated';
import { Head } from '@inertiajs/inertia-react';
import FeedsList from '@/Components/FeedsList';
import ArticleFeed from '@/Components/ArticleFeed';

export default function Feeds(props) {
  return (
    <Authenticated errors={props.errors}>
      <Head title="Feeds" />

      <div className="flex">
        <FeedsList feeds={props.feeds} />
        <div className="flex-1">
          <ArticleFeed articles={props.articles} />
        </div>
      </div>
    </Authenticated>
  );
}
