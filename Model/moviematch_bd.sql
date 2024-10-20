-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/08/2024 às 00:04
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `moviematch_bd`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacao_filmes`
--

CREATE TABLE `avaliacao_filmes` (
  `id_avalia_filme` int(11) NOT NULL,
  `fk_filme_api` int(11) DEFAULT NULL,
  `fk_usuario` int(11) DEFAULT NULL,
  `nota` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avalia_comentarios`
--

CREATE TABLE `avalia_comentarios` (
  `id_reacao` int(11) NOT NULL,
  `fk_comentario` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `tipo` enum('LIKE','DISLIKE') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `fk_filme_api` int(11) NOT NULL,
  `texto` text NOT NULL,
  `data_comentario` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios_foruns`
--

CREATE TABLE `comentarios_foruns` (
  `id_comenta_forum` int(11) NOT NULL,
  `fk_forum` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curtidas_foruns`
--

CREATE TABLE `curtidas_foruns` (
  `id_like` int(11) NOT NULL,
  `fk_forum` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `data_like` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `filmes_favoritos`
--

CREATE TABLE `filmes_favoritos` (
  `id_filme_favorito` int(11) NOT NULL,
  `fk_filme_api` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `filmes_favoritos`
--

INSERT INTO `filmes_favoritos` (`id_filme_favorito`, `fk_filme_api`, `fk_usuario`) VALUES
(3, 487558, 10),
(4, 419430, 10),
(5, 569094, 10),
(6, 583406, 10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `filmes_salvos`
--

CREATE TABLE `filmes_salvos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_filme_api` int(11) NOT NULL,
  `data_salvamento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `foruns`
--

CREATE TABLE `foruns` (
  `id_forum` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `foruns`
--

INSERT INTO `foruns` (`id_forum`, `fk_usuario`, `titulo`, `descricao`, `data_criacao`) VALUES
(14, 22, 'A Evolução do Cinema Clássico: De Chaplin a Hitchcock', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 12:47:57'),
(15, 23, 'Os Maiores Blockbusters da Década de 2020', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 12:49:59'),
(16, 10, 'Marvel vs DC: Qual Universo Cinematográfico é Melhor?', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 12:52:24'),
(18, 24, 'Filmes Indie que Você Precisa Assistir', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 12:56:27'),
(19, 25, 'Os Filmes de Terror Mais Assustadores de Todos os Tempos', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 12:58:17'),
(20, 26, 'Como a Ficção Científica Previu o Futuro', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 12:59:48'),
(21, 27, 'A Influência do Studio Ghibli no Cinema Mundial', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:02:54'),
(22, 22, 'Como o Cinema Mudo Influenciou as Produções Modernas?', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:04:36'),
(23, 23, 'A Evolução dos Efeitos Visuais em Hollywood', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:05:57'),
(25, 25, 'A Evolução do Gênero Slasher', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:07:58'),
(26, 22, 'Como os Documentários Podem Mudar o Mundo', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:08:41'),
(27, 10, 'Os Melhores Animes de Todos os Tempos', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:09:33'),
(28, 28, 'Os Filmes de Romance Mais Emocionantes', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:11:11'),
(29, 23, 'Heróis de Ação: Quem é o Melhor de Todos os Tempos?', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:16:18'),
(30, 22, 'Os Clássicos do Cinema que Você Precisa Ver', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:17:39'),
(31, 28, 'As Melhores Comédias Românticas para Assistir a Dois', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:19:44'),
(32, 25, 'Gente grande 2 é melhor q o primeiro', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:21:30'),
(33, 24, 'A Magia dos Filmes dos Anos 50 e 60', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:23:48'),
(34, 10, 'A Evolução dos Filmes de Super-herói', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:24:41'),
(35, 28, 'Melhores Filmes da Disney', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:26:37'),
(36, 23, 'A Importância dos Efeitos Especiais em Filmes de Ficção Científica', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 13:27:49'),
(38, 25, 'Filmes de cachorros para aquecer o coração', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', '2024-08-05 17:08:42');

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas_foruns`
--

CREATE TABLE `respostas_foruns` (
  `id_resposta` int(11) NOT NULL,
  `fk_forum` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `resposta` text NOT NULL,
  `data_resposta` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `foto` longblob NOT NULL,
  `admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha`, `descricao`, `foto`, `admin`) VALUES
(10, 'vini', 'vinibalsemao@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas cursus dignissim libero gravida rutrum. Quisque cursus interdum dolor, consectetur euismod sem vulputate sed. Cras sollicitudin, risus at scelerisque lobortis, diam tellus placerat orci, fermentum interdum augue leo ac enim. Nulla sit amet ultrices justo, vel dignissim nulla. Nulla eget tincidunt ligula. Morbi iaculis libero sed augue tempus fringilla. Donec ornare sed tortor a dapibus. Aenean non euismod ipsum.', 0x32663663383232383964393038623662613065373463633937326264656437372e6a7067, 0),
(22, 'JoãoCinefilo', 'joao.cinefilo@example.com', 'e99a18c428cb38d5f260853678922e03', '', 0x36353730316232383533326434653735303430326130346463343938343935632e6a7067, 0),
(23, 'AnneGameplays2020', 'ana.blockbuster@example.com', 'e99a18c428cb38d5f260853678922e03', '', 0x34343664396331316132316363373933613831306435613733386566393937322e6a7067, 0),
(24, 'PedroIndie', 'pedro.indie@example.com', 'e99a18c428cb38d5f260853678922e03', '', '', 0),
(25, 'Mari do AUmigos', 'clara.terror@example.com', 'e99a18c428cb38d5f260853678922e03', '', 0x31316661613931333937333335303964656265323734653664666537353963652e6a7067, 0),
(26, 'LucasSciFi', 'lucas.scifi@example.com', 'e99a18c428cb38d5f260853678922e03', '', 0x61393663343631343934613636656334313433656166613163663538313333322e6a7067, 0),
(27, 'RafaelAnimes', 'rafael.animes@example.com', 'e99a18c428cb38d5f260853678922e03', '', 0x30356333303733653863353363643539613462656263653063373032363831372e6a7067, 0),
(28, 'Gabizinha', 'gabi.romantica@example.com', 'e99a18c428cb38d5f260853678922e03', '', '', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `avaliacao_filmes`
--
ALTER TABLE `avaliacao_filmes`
  ADD PRIMARY KEY (`id_avalia_filme`);

--
-- Índices de tabela `avalia_comentarios`
--
ALTER TABLE `avalia_comentarios`
  ADD PRIMARY KEY (`id_reacao`),
  ADD KEY `fk_comentario` (`fk_comentario`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Índices de tabela `comentarios_foruns`
--
ALTER TABLE `comentarios_foruns`
  ADD PRIMARY KEY (`id_comenta_forum`),
  ADD KEY `fk_forum` (`fk_forum`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Índices de tabela `curtidas_foruns`
--
ALTER TABLE `curtidas_foruns`
  ADD PRIMARY KEY (`id_like`),
  ADD KEY `fk_forum` (`fk_forum`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Índices de tabela `filmes_favoritos`
--
ALTER TABLE `filmes_favoritos`
  ADD PRIMARY KEY (`id_filme_favorito`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Índices de tabela `filmes_salvos`
--
ALTER TABLE `filmes_salvos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`,`id_filme_api`);

--
-- Índices de tabela `foruns`
--
ALTER TABLE `foruns`
  ADD PRIMARY KEY (`id_forum`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Índices de tabela `respostas_foruns`
--
ALTER TABLE `respostas_foruns`
  ADD PRIMARY KEY (`id_resposta`),
  ADD KEY `fk_forum` (`fk_forum`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avaliacao_filmes`
--
ALTER TABLE `avaliacao_filmes`
  MODIFY `id_avalia_filme` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `avalia_comentarios`
--
ALTER TABLE `avalia_comentarios`
  MODIFY `id_reacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `comentarios_foruns`
--
ALTER TABLE `comentarios_foruns`
  MODIFY `id_comenta_forum` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curtidas_foruns`
--
ALTER TABLE `curtidas_foruns`
  MODIFY `id_like` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `filmes_favoritos`
--
ALTER TABLE `filmes_favoritos`
  MODIFY `id_filme_favorito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `filmes_salvos`
--
ALTER TABLE `filmes_salvos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de tabela `foruns`
--
ALTER TABLE `foruns`
  MODIFY `id_forum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de tabela `respostas_foruns`
--
ALTER TABLE `respostas_foruns`
  MODIFY `id_resposta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avalia_comentarios`
--
ALTER TABLE `avalia_comentarios`
  ADD CONSTRAINT `avalia_comentarios_ibfk_1` FOREIGN KEY (`fk_comentario`) REFERENCES `comentarios` (`id_comentario`) ON DELETE CASCADE,
  ADD CONSTRAINT `avalia_comentarios_ibfk_2` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `comentarios_foruns`
--
ALTER TABLE `comentarios_foruns`
  ADD CONSTRAINT `comentarios_foruns_ibfk_1` FOREIGN KEY (`fk_forum`) REFERENCES `foruns` (`id_forum`),
  ADD CONSTRAINT `comentarios_foruns_ibfk_2` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Restrições para tabelas `curtidas_foruns`
--
ALTER TABLE `curtidas_foruns`
  ADD CONSTRAINT `curtidas_foruns_ibfk_1` FOREIGN KEY (`fk_forum`) REFERENCES `foruns` (`id_forum`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `curtidas_foruns_ibfk_2` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `filmes_favoritos`
--
ALTER TABLE `filmes_favoritos`
  ADD CONSTRAINT `filmes_favoritos_ibfk_1` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Restrições para tabelas `filmes_salvos`
--
ALTER TABLE `filmes_salvos`
  ADD CONSTRAINT `filmes_salvos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Restrições para tabelas `foruns`
--
ALTER TABLE `foruns`
  ADD CONSTRAINT `foruns_ibfk_1` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Restrições para tabelas `respostas_foruns`
--
ALTER TABLE `respostas_foruns`
  ADD CONSTRAINT `respostas_foruns_ibfk_1` FOREIGN KEY (`fk_forum`) REFERENCES `foruns` (`id_forum`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `respostas_foruns_ibfk_2` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
