// src/pages/Contatos.js
import React, { useState } from 'react';
import FormularioContato from '../components/Contato/formularioContato';
import TabelaContato from '../components/Contato/tabelaContato';

const Contatos = () => {
  const [contatoSelecionado, setContatoSelecionado] = useState(null);

  return (
    <div>
      <h2 class="text-blue-900 font-semibold mt-5 text-center text-xl">Lista de contatos e cadastro</h2>
      <FormularioContato contatoSelecionado={contatoSelecionado} onContatoSalvo={() => setContatoSelecionado(null)} />
      <TabelaContato onEdit={(contato) => setContatoSelecionado(contato)} />
    </div>
  );
};

export default Contatos;
