---
date: '2026-05-27T20:00:37-03:00'
draft: true
title: 'Landingpage Estudioz'
---

## Responsabilidades
Programar um site responsivo, que deve possuir uma paleta de cores dinâmica (ao recarregar a tela, as cores devem trocar) e também dar um mínimo de autonomia ao cliente: as seções devem ser dinâmicas de acordo com pastas que são criadas com conteúdo no lado do servidor.

Configurar o servidor e deploy do site, projeto não possui base de dados, apenas usa o sistema de arquivos.

## Tecnologias
Site foi construído usando apenas php, sem framework, usando um forte paradigma de MVC e Orientação a Objetos. Já para o front end, tecnologias base: HTML5, javascript e framework tailwindcss.

## Características
- Paleta de cores dinamicas.
- Seções da página dinâmicas.
- Envio de emails.
- Ferramentas do Google Analyics, Search Console e Google Ads configurados.

## Desafios encontrados
1. Alterar a paleta de cores da página foi inusitado para mim. Problema foi solucionado usando um cookie de sessão que guarda qual tema está sendo visto, e uma lista estática de cores repassadas pelo cliente. Essa lista é iterada e altera valores da variável css.
1. Exibir os livros do estúdio num estilo de revista.