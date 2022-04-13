import React from 'react';
import Authenticated from '@/Layouts/Authenticated';
import { Head } from '@inertiajs/inertia-react';
import ArticleFeed from '@/Components/ArticleFeed';

export default function Saved(props) {
  return (
    <Authenticated>
      <Head title="Saved" />

      <ArticleFeed articles={props.articles} />
    </Authenticated>
  );
}
