import React from 'react';
import Navbar from '@/Components/Navbar';
import FlashMessages from '@/Components/FlashMessages';

export default function Authenticated({ children }) {
  return (
    <div className="min-h-screen bg-gray-100">
      <Navbar />
      <main className="max-w-7xl mx-auto mt-10 px-10">{children}</main>
      <FlashMessages />
    </div>
  );
}
