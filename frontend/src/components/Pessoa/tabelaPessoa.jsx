import React from 'react';

const TabelaPessoa = ({ pessoas, onEdit, onDelete }) => {
  return (
    <div className="overflow-x-auto">
      <table className="min-w-full bg-white border border-gray-200">
        <thead>
          <tr className="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
            <th className="py-2 px-4 border-b">ID</th>
            <th className="py-2 px-4 border-b">Nome</th>
            <th className="py-2 px-4 border-b">CPF</th>
            <th className="py-2 px-4 border-b">Ações</th>
          </tr>
        </thead>
        <tbody className="text-gray-600 text-sm font-light">
          {pessoas.map((pessoa) => (
            <tr key={pessoa.id} className="hover:bg-gray-100">
              <td className="py-2 px-4 border-b">{pessoa.id}</td>
              <td className="py-2 px-4 border-b">{pessoa.nome}</td>
              <td className="py-2 px-4 border-b">{pessoa.cpf}</td>
              <td className="py-2 px-4 border-b">
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
