import React, { useState, useEffect } from 'react';
import { salvarContato, atualizarContato, fetchPessoas } from '../../services/api';

const FormularioContato = ({ contatoSelecionado, onContatoSalvo }) => {
  const [contato, setContato] = useState(contatoSelecionado || { nome: '', email: '' });
  const [pessoas, setPessoas] = useState([]);

  useEffect(() => {
    if (contatoSelecionado) {
      setContato(contatoSelecionado);
    } else {
      fetchPessoas().then((response) => setPessoas(response.data));
    }
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setContato((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (contatoSelecionado) {
      atualizarContato(contato.id, contato).then(onContatoSalvo);
    } else {
      salvarContato(contato).then(onContatoSalvo);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="bg-white p-6 rounded shadow-md">
    <div className="mb-4">
      <label className="block text-gray-700 text-sm font-bold mb-2 text-left" htmlFor="pessoaId">
        Nome:
      </label>
      <select
        name="pessoaId"
        value={contato.pessoaId}
        onChange={handleChange}
        required
        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
      >
        <option value="">Selecione uma pessoa</option>
        {pessoas.map((pessoa) => (
          <option key={pessoa.id} value={pessoa.id}>
            {pessoa.nome}
          </option>
        ))}
      </select>
    </div>

    <div className="mb-4">
      <label className="block text-gray-700 text-sm font-bold mb-2 text-left" htmlFor="email">
        Contato:
      </label>
      <input
        type="text"
        name="descricao"
        value={contato.descricao}
        onChange={handleChange}
        required
        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
      />
    </div>

    <button
      type="submit"
      className="bg-blue-500 justify-start flex hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
    >
      {contatoSelecionado ? 'Atualizar' : 'Salvar'}
    </button>
  </form>
  );
};

export default FormularioContato;
