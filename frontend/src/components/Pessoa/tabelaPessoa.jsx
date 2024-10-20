import React, { useEffect, useState } from 'react';
import { fetchPessoas, deletarPessoa } from '../../services/api';

const TabelaPessoa = ({ pessoas, onEdit, onDelete }) => {
  return (
    <div className="overflow-x-auto">
      <table className="min-w-full bg-white border border-gray-200">
        <thead>
          <tr className="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
            <th className="py-3 px-6 text-left">ID</th>
            <th className="py-3 px-6 text-left">Nome</th>
            <th className="py-3 px-6 text-left">CPF</th>
            <th className="py-3 px-6 text-left">Ações</th>
          </tr>
        </thead>
        <tbody className="text-gray-600 text-sm font-light">
          {pessoas.map((pessoa) => (
            <tr key={pessoa.id} className="border-b border-gray-200 hover:bg-gray-100">
              <td className="py-3 px-6">{pessoa.id}</td>
              <td className="py-3 px-6">{pessoa.nome}</td>
              <td className="py-3 px-6">{pessoa.cpf}</td>
              <td className="py-3 px-6 flex space-x-2">
                <button 
                  onClick={() => onEdit(pessoa)} 
                  className="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition duration-200 mr-2"
                >
                  Editar
                </button>
                <button 
                  onClick={() => onDelete(pessoa.id)} 
                  className="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition duration-200"
                >
                  Excluir
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default TabelaPessoa;
