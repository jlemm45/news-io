import React from 'react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link, usePage } from '@inertiajs/inertia-react';

const Navbar = () => {
  const {
    auth: { user },
  } = usePage().props;

  return (
    <div className="bg-base-100 shadow sticky top-0 z-50">
      <div className="navbar max-w-7xl mx-auto px-10">
        <div className="flex-1">
          <Link href="/" className="h-12">
            <ApplicationLogo className="fill-green-500" />
          </Link>
        </div>
        <div className="flex-none">
          <div className="dropdown dropdown-end">
            <label tabIndex="0" className="btn btn-ghost btn-circle avatar">
              <div className="w-10 rounded-full">
                <img src={user.avatar} />
              </div>
            </label>
            <ul
              tabIndex="0"
              className="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52"
            >
              <li>
                <a href={route('saved')}>My Saved</a>
              </li>
              <li>
                <a className="justify-between" href={route('profile')}>
                  Profile
                  <span className="badge">New</span>
                </a>
              </li>
              <li>
                <a href={route('logout')}>Logout</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Navbar;
