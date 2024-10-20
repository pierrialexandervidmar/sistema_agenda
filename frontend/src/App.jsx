import './App.css';
import { BrowserRouter as Router, Route, Routes, Navigate } from 'react-router-dom';
import Navbar from './components/navbar';
import Pessoas from './pages/Pessoas';
import Contatos from './pages/Contatos';

function App() {
  return (
    <Router>
      <div>
        <Navbar />
        <Routes>
          <Route path="/" element={<Navigate to="/contatos" />} /> {/* Redireciona para /pessoas */}
          <Route path="/pessoas" element={<Pessoas />} />
          <Route path="/contatos" element={<Contatos />} />
        </Routes>
      </div>
    </Router>
  );
}

export default App;
