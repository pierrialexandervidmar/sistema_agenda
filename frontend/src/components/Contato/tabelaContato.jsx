import React, { useEffect, useState } from 'react';
import { fetchContatos, deletarContato } from '../../services/api';

const TabelaContato = ({ onEdit }) => {
  const [contatos, setContatos] = useState([]);

  useEffect(() => {
    fetchContatos().then((response) => setContatos(response.data));
  }, []);

  const handleDelete = (id) => {
    deletarContato(id).then(() => setContatos(contatos.filter((c) => c.id !== id)));
  };

  return (
    <div className="overflow-x-auto">
      <table className="min-w-full bg-white border border-gray-200">
        <thead>
          <tr className="bg-gray-200 text-gray-700">
            <th className="py-2 px-4 border-b">ID</th>
            <th className="py-2 px-4 border-b">Nome</th>
            <th className="py-2 px-4 border-b">Contato</th>
            <th className="py-2 px-4 border-b">Ações</th>
          </tr>
        </thead>
        <tbody>
          {contatos.map((contato) => (
            <tr key={contato.id} className="hover:bg-gray-100">
              <td className="py-2 px-4 border-b">{contato.id}</td>
              <td className="py-2 px-4 border-b">{contato.pessoa.nome}</td>
              <td className="py-2 px-4 border-b">{contato.descricao}</td>
              <td className="py-2 px-4 border-b">
                <button
                  onClick={() => onEdit(contato)}
                  className="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition duration-200 mr-2"
                >
                  Editar
                </button>
                <button
                  onClick={() => handleDelete(contato.id)}
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

export default TabelaContato;
