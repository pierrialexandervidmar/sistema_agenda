import React from 'react';
import { Link } from 'react-router-dom';

const Navbar = () => {
  return (
    <nav className="bg-blue-600 p-4">
      <ul className="flex space-x-4">
        <li>
          <Link
            to="/contatos"
            className="text-white hover:bg-blue-700 px-3 py-2 rounded transition duration-200"
          >
            Contatos
          </Link>
        </li>
        <li>
          <Link
            to="/pessoas"
            className="text-white hover:bg-blue-700 px-3 py-2 rounded transition duration-200"
          >
            Pessoas
          </Link>
        </li>
      </ul>
    </nav>
  );
};

export default Navbar;
