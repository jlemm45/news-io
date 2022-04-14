import { useForm, usePage } from '@inertiajs/inertia-react';
import React, { useEffect } from 'react';
import { AiOutlineCheck } from 'react-icons/ai';
import { useList } from 'react-use';
import Button from './Button';

const FeedSelector = () => {
  const { feeds } = usePage().props;
  const [list, { push, remove }] = useList([]);
  const { post, setData } = useForm({ feeds: [] });

  const getStarted = e => {
    e.preventDefault();
    post(route('feeds.select'));
  };

  useEffect(() => {
    setData({ feeds: list });
  }, [list]);

  return (
    <>
      <div className="bg-white rounded shadow grid grid-cols-8 gap-4 mt-10 p-4">
        {feeds.map(feed => {
          const isSelected = list.includes(feed.id);
          return (
            <button
              key={feed.id}
              className="bg-white rounded shadow p-4"
              onClick={e => {
                e.preventDefault();
                if (isSelected) {
                  remove(feed.id);
                } else {
                  push(feed.id);
                }
              }}
            >
              {isSelected && (
                <div className="absolute bg-white opacity-80">
                  <AiOutlineCheck size={18} />
                </div>
              )}
              <img src={feed.favicon} />
            </button>
          );
        })}
      </div>
      {!!list.length && (
        <Button className="mt-4" onClick={getStarted}>
          Get Started
        </Button>
      )}
    </>
  );
};

export default FeedSelector;
