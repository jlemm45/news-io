import { usePage } from '@inertiajs/inertia-react';
import React, { useEffect, useState } from 'react';

const FlashMessages = () => {
  const [visible, setVisible] = useState(false);
  const { flash } = usePage().props;

  useEffect(() => {
    if (flash.success && !visible) {
      setVisible(true);
      setTimeout(() => {
        setVisible(false);
      }, 3000);
    }
  }, [flash]);

  return visible ? (
    <div className="fixed bottom-10 left-0 right-0 mx-auto w-fit">
      <div className="alert alert-success shadow-lg">
        <div>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            className="stroke-current flex-shrink-0 h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <span>{flash.success}</span>
        </div>
      </div>
    </div>
  ) : null;
};

export default FlashMessages;
