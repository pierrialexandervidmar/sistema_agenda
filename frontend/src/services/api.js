// services/api.js
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8080/api', // URL da sua API
});

export const fetchPessoas = () => api.get('/pessoas');
export const fetchContatos = () => api.get('/contatos/pessoas');
export const salvarPessoa = (pessoa) => api.post('/pessoas', pessoa);
export const salvarContato = (contato) => api.post('/contatos', contato);
export const atualizarPessoa = (id, pessoa) => api.put(`/pessoas/${id}`, pessoa);
export const atualizarContato = (id, contato) => api.put(`/contatos/${id}`, contato);
export const deletarPessoa = (id) => api.delete(`/pessoas/${id}`);
export const deletarContato = (id) => api.delete(`/contatos/${id}`);

export default api;
