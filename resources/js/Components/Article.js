import { truncate } from 'lodash';
import React from 'react';
import day from 'dayjs';
import { AiFillSave } from 'react-icons/ai';

const Article = ({ article, onSelect }) => {
  return (
    <div className="card bg-base-100 shadow-xl">
      {article.img && (
        <figure>
          <img src={article.img} alt={article.title} />
        </figure>
      )}
      <div className="card-body">
        <img src={article.favicon} className="w-4" alt={article.title} />
        <p className="text-sm">Posted {day(article.posted_at).fromNow()}</p>
        <h2 className="card-title">{article.title}</h2>
        <p>{truncate(article.cleaned, { length: 500 })}</p>
        <div className="card-actions justify-between items-center">
          <a
            className="link"
            onClick={() => {
              onSelect(article);
            }}
          >
            Read More
          </a>
          <button className="btn btn-outline gap-2">
            <AiFillSave size={20} />
          </button>
        </div>
      </div>
    </div>
  );
};

export default Article;
