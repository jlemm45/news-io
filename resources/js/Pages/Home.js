import React from 'react';
import { Link, Head } from '@inertiajs/inertia-react';
import ApplicationLogo from '@/Components/ApplicationLogo';

export default function Home(props) {
  return (
    <>
      <Head title="Home" />
      <div className="min-h-screen bg-slate-100">
        <div className="w-screen bg-green-500">
          <div className="fixed top-0 right-0 px-6 py-4 sm:block">
            {props.auth.user ? (
              <Link
                href={route('feeds')}
                className="text-sm text-white border-white border-2 px-4 py-2 rounded"
              >
                Dashboard
              </Link>
            ) : (
              <>
                <Link
                  href={route('login')}
                  className="text-sm text-white border-white border-2 px-4 py-2 rounded"
                >
                  Log in
                </Link>

                <Link
                  href={route('register')}
                  className="ml-4 text-sm text-white border-white border-2 px-4 py-2 rounded"
                >
                  Register
                </Link>
              </>
            )}
          </div>
          <div className="h-96 pt-40 flex justify-center">
            <div className="w-1/3">
              <ApplicationLogo className="fill-white" />
            </div>
          </div>
        </div>
        <div className="max-w-lg m-auto text-sm mt-10">
          <p className="mb-4">
            SnugFeed let's you stay up to date will all your favorite news
            sources in one place updated in real-time!
          </p>
          <p className="mb-4">
            Select from some of our pre-existing feeds below to try it out.
          </p>
          <p className="mb-4">
            Register and get access to add as many feeds and you want as well as
            your own custom feeds!
          </p>
          <div className="bg-white rounded shadow grid grid-cols-8 gap-4 mt-10 p-4">
            {props.feeds.map(feed => (
              <div className="bg-white rounded shadow p-4">
                <img src={feed.favicon} />
              </div>
            ))}
          </div>
        </div>
      </div>
    </>
  );
}
