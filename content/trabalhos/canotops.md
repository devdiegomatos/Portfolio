---
date: '2026-05-27T21:48:56-03:00'
draft: true
title: 'Canotops'

cover:
  image: "canotops.png"
  alt: "Projeto Canotops"
  caption: "Configurador de Tendas 3D"
---

## Introdução
Atuei como desenvolvedor full stack criando um plugin WordPress personalizado capaz de substituir a página de produto do WooCommerce por um configurador 3D avançado construído em Three.js. O sistema permite ao usuário alterar cores, materiais, textos, estampas e outros atributos visuais, tudo renderizado em tempo real diretamente no navegador. Além da parte visual, implementei uma camada de automação que gera automaticamente mockups e arquivos vetoriais para o setor de produção, reduzindo o tempo entre o pedido e a fabricação. Toda a customização feita pelo cliente é salva e integrada ao fluxo padrão do WooCommerce, incluindo carrinho, checkout e histórico de pedidos.

## Responsabilidades
- Desenvolver plugin WordPress customizado integrado ao WooCommerce.
- Criar e otimizar o configurador 3D usando Three.js com renderização interativa.
- Implementar lógica de personalização (cores, materiais, textos, decals).
- Gerar mockups e arquivos vetoriais automaticamente para produção.
- Armazenar as customizações do cliente no fluxo padrão do WooCommerce.
- Assegurar compatibilidade entre front-end 3D, backend WooCommerce e banco de dados.
- Garantir performance e boa experiência do usuário em dispositivos variados.

## Tecnologias
- WordPress / WooCommerce
- PHP (hooks, endpoints, lógica de persistência)
- JavaScript / ES Modules
- Three.js (renderização 3D, materiais, texturas, canvas)
- WebGL
- HTML / CSS
- REST API (comunicação WP ↔ configurador)
- Ferramentas de geração de imagem/vetor Fabric.js

## Características
- Configurador 3D totalmente integrado ao WooCommerce.
- Renderização em tempo real com materiais configuráveis.
- UI responsiva para desktop e mobile.
- Sistema de exportação automática para mockups/vetores.
- Armazenamento limpo e seguro das customizações.
- Arquitetura extensível para futuras variações de produtos.

## Desafios encontrados
- Garantir performance do Three.js em dispositivos mais fracos.
- Otimizar UV mapping para permitir personalização correta nas superfícies da tenda.
- Manter compatibilidade com o fluxo padrão do WooCommerce sem alterar o core.
- Sincronizar as personalizações 3D com dados persistidos no backend.
- Gerar arquivos vetoriais precisos e tecnicamente utilizáveis pela produção.
- Controle de cache e carregamento rápido dos assets 3D e texturas.
- Evitar perdas de customização durante navegação entre páginas.

{{< video src="/videos/canotops-apresentacao.mp4">}}
