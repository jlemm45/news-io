import { truncate } from 'lodash';
import React from 'react';
import day from 'dayjs';
import { AiFillSave, AiFillDelete } from 'react-icons/ai';
import { useForm } from '@inertiajs/inertia-react';
import Button from './Button';

const Article = ({ article, onSelect }) => {
  const { post, delete: destroy, processing } = useForm();

  const save = () => {
    if (article.is_saved) {
      return destroy(route('saved.delete', { article: article.id }), {
        preserveScroll: true,
      });
    }

    return post(route('saved.add', { article: article.id }), {
      preserveScroll: true,
    });
  };

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
          <Button
            className="btn btn-outline gap-2"
            onClick={save}
            processing={processing}
          >
            {article.is_saved ? (
              <AiFillDelete size={20} />
            ) : (
              <AiFillSave size={20} />
            )}
          </Button>
        </div>
      </div>
    </div>
  );
};

export default Article;
