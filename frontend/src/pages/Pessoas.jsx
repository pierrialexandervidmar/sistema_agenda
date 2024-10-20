import React, { useState, useEffect } from 'react';
import FormularioPessoa from '../components/Pessoa/formularioPessoa';
import TabelaPessoa from '../components/Pessoa/tabelaPessoa';
import { deletarPessoa, fetchPessoas } from '../services/api';

const Pessoas = () => {
  const [pessoas, setPessoas] = useState([]);
  const [pessoaSelecionada, setPessoaSelecionada] = useState(null);

  useEffect(() => {
    carregarPessoas();
  }, []);

  const carregarPessoas = () => {
    fetchPessoas().then((response) => {
      console.log(response.data);
      setPessoas(response.data);
    });
  };

  const handleEdit = (pessoa) => {
    setPessoaSelecionada(pessoa);
  };

  const handleDelete = (id) => {
    deletarPessoa(id).then(() => {
      setPessoas(pessoas.filter((p) => p.id !== id));
    });
  };

  const handlePessoaSalva = () => {
    setPessoaSelecionada(null);
    carregarPessoas();
  };

  return (
    <div>
      <h1 className="text-blue-900 font-semibold mt-5 text-center text-xl">Lista e cadastro de pessoas</h1>
      <FormularioPessoa
        pessoaSelecionada={pessoaSelecionada}
        onPessoaSalva={handlePessoaSalva}
      />
      <TabelaPessoa
        pessoas={pessoas}
        onEdit={handleEdit}
        onDelete={handleDelete}
      />
    </div>
  );
};

export default Pessoas;
