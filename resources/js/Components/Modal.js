import React, { useEffect } from 'react';

const Modal = ({ children, visible, onClose, modalID, className }) => {
  useEffect(() => {
    document.getElementById(modalID).checked = visible;
    if (visible) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'visible';
    }
  }, [visible]);

  return (
    <div>
      <input type="checkbox" id={modalID} className="modal-toggle" />
      <div className="modal" htmlFor={modalID} onClick={onClose}>
        <div
          className={`modal-box relative bg-gray-100 ${className}`}
          onClick={e => {
            e.stopPropagation();
          }}
        >
          {children}
        </div>
      </div>
    </div>
  );
};

export default Modal;
