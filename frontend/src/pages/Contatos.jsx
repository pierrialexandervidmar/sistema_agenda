import React, { useEffect, useState } from 'react';
import FormularioContato from '../components/Contato/formularioContato';
import TabelaContato from '../components/Contato/tabelaContato';
import { deletarContato, fetchContatos } from '../services/api';

const Contatos = () => {
  const [contatos, setContatos] = useState([]);
  const [contatoSelecionado, setContatoSelecionado] = useState(null);

  useEffect(() => {
    carregarContatos();
  }, []);

  const carregarContatos = () => {
    fetchContatos().then((response) => {
      setContatos(response.data);
    });
  };

  const handleEdit = (contato) => {
    console.log(contato);
    setContatoSelecionado(contato);
  };

  const handleDelete = (id) => {
    deletarContato(id).then(() => {
      setContatos(contatos.filter((p) => p.id !== id));
    });
  };

  const handleContatoSalvo = () => {
    setContatoSelecionado(null);
    carregarContatos();
  };

  return (
    <div>
      <h2 class="text-blue-900 font-semibold mt-5 text-center text-xl">Lista de contatos e cadastro</h2>
      <FormularioContato 
        contatoSelecionado={contatoSelecionado} 
        onContatoSalvo={handleContatoSalvo} 
      />
      <TabelaContato
        contatos={contatos}
        onEdit={handleEdit} 
        onDelete={handleDelete}
      />
    </div>
  );
};

export default Contatos;
