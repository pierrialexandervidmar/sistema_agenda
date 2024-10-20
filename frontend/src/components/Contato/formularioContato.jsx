import React, { useState, useEffect } from 'react';
import { salvarContato, atualizarContato, fetchPessoas } from '../../services/api';
import Contatos from '../../pages/Contatos';

const FormularioContato = ({ contatoSelecionado, onContatoSalvo }) => {
  const [contato, setContato] = useState(contatoSelecionado || { tipo: '', descricao: '', idPessoa: 0 });
  const [pessoas, setPessoas] = useState([]);

  useEffect(() => {
    if (contatoSelecionado) {
      setContato({
        tipo: contatoSelecionado.tipo === 'Email' ? 1 : 0,
        descricao: contatoSelecionado.descricao,
        idPessoa: contatoSelecionado.pessoa.id
      });
    } else {
      fetchPessoas().then((response) => setPessoas(response.data));
    }
  }, [contatoSelecionado]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setContato((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    const payload = {
      tipo: Number(contato.tipo),
      descricao: contato.descricao,
      idPessoa: Number(contato.idPessoa)
    };

    if (contatoSelecionado) {
      atualizarContato(contatoSelecionado.id, payload).then(onContatoSalvo);
      setContato({ tipo: '', descricao: '', idPessoa: 0 });
    } else {
      console.log(payload);
      salvarContato(payload).then(onContatoSalvo);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="bg-white p-6 rounded shadow-md">
      <div className="mb-4">
        <label className="block text-gray-700 text-sm font-bold mb-2 text-left" htmlFor="idPessoa">
          Nome:
        </label>
        <select
          name="idPessoa"
          value={contato.pessoaId}
          onChange={handleChange}
          required
          className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
          {!contatoSelecionado && (
            <option value="">Selecione uma pessoa</option>
          )}

          {contatoSelecionado
            ?
            <option value={contatoSelecionado.idPessoa}>{contatoSelecionado.pessoa.nome}</option>
            :
            pessoas.map((pessoa) => (
              <option key={pessoa.id} value={pessoa.id}>
                {pessoa.nome}
              </option>
            ))}
        </select>
      </div>

      <div className="mb-4">
        <label className="block text-gray-700 text-sm font-bold mb-2 text-left" htmlFor="pessoaId">
          Tipo:
        </label>
        <select
          name="tipo"
          value={contato.tipo}
          onChange={handleChange}
          required
          className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
          {contatoSelecionado ? (
            <>
              <option value={1} selected={contato.tipo === "Email"}>Email</option>
              <option value={0} selected={contato.tipo === "Telefone"}>Telefone</option>
            </>
          ) : (
            <>
              <option value={1}>Email</option>
              <option value={0}>Telefone</option>
            </>
          )}
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
