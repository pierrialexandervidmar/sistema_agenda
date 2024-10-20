import React, { useState, useEffect } from 'react';
import { salvarPessoa, atualizarPessoa } from '../../services/api';

const FormularioPessoa = ({ pessoaSelecionada, onPessoaSalva }) => {
  const [pessoa, setPessoa] = useState(pessoaSelecionada || { nome: '', cpf: '' });

  useEffect(() => {
    setPessoa(pessoaSelecionada || { nome: '', cpf: '' });
  }, [pessoaSelecionada]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setPessoa((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (pessoaSelecionada) {
      atualizarPessoa(pessoa.id, pessoa).then(() => onPessoaSalva());
    } else {
      salvarPessoa(pessoa).then(() => onPessoaSalva());
    }
  };

  return (
    <form onSubmit={handleSubmit} className="bg-white p-6 rounded shadow-md">
      <div className="mb-4">
        <label className="block text-gray-700 text-sm font-bold mb-2 text-left">Nome:</label>
        <input
          type="text"
          name="nome"
          value={pessoa.nome}
          onChange={handleChange}
          required
          className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        />
      </div>
      <div className="mb-4">
        <label className="block text-gray-700 text-sm font-bold mb-2 text-left">CPF:</label>
        <input
          type="text"
          name="cpf"
          value={pessoa.cpf}
          onChange={handleChange}
          required
          className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        />
      </div>
      <button
        type="submit"
        className="bg-blue-500 flex justify-start hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
      >
        {pessoaSelecionada ? 'Atualizar' : 'Salvar'}
      </button>
    </form>
  );
};

export default FormularioPessoa;
