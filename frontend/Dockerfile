# Usar a imagem oficial do Node.js
FROM node:18

# Definir o diretório de trabalho
WORKDIR /app

# Copiar o package.json e o package-lock.json (ou yarn.lock) para instalar dependências
COPY package*.json ./

# Instalar as dependências da aplicação
RUN npm install

# Copiar o restante do código fonte
COPY . .

# Build da aplicação
RUN npm run build

# Expor a porta que a aplicação irá rodar
EXPOSE 5173

# Comando para iniciar a aplicação (pode variar dependendo de como você configura o Vite)
CMD ["npm", "run", "dev"]