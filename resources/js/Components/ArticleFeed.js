import React, { useState } from 'react';
import Masonry, { ResponsiveMasonry } from 'react-responsive-masonry';
import Article from './Article';
import Modal from './Modal';

const ArticleFeed = ({ articles }) => {
  const [activeArticle, setArticle] = useState(false);

  return (
    <>
      <ResponsiveMasonry columnsCountBreakPoints={{ 350: 1, 1000: 2, 1400: 3 }}>
        <Masonry gutter={20}>
          {articles.map(article => (
            <Article
              onSelect={article => {
                setArticle(article);
              }}
              key={article.id}
              article={article}
            />
          ))}
        </Masonry>
      </ResponsiveMasonry>
      <Modal
        className="max-w-5xl"
        modalID="read-modal"
        visible={!!activeArticle}
        onClose={() => {
          setArticle(false);
        }}
      >
        <h2 className="card-title">{activeArticle.title}</h2>
        <div
          dangerouslySetInnerHTML={{
            __html: activeArticle.description,
          }}
        />
      </Modal>
    </>
  );
};

export default ArticleFeed;
