---
date: '2026-05-27T21:49:54-03:00'
draft: true
title: 'Cuidado E Afeto'
---

Plataforma para marcar consulta com profissionais de Enfermagem e Cuidadores Pessoais no Rio de Janeiro.

<!-- more -->

## Introdução
Desenvolver um site cujo objetivo será reunir profissionais qualificados de enfermagem e cuidadores pessoais onde os clientes irão acessar o site, acessar o catálogo de profissionais e selecionar um profissional disponível para atender um paciente.

Cada consulta conterá informação de: quem é o cliente solicitante, o paciente, quem é o profissional, a data e hora da consulta, a jornada de trabalho, o número da consulta, valor da consulta, forma de pagamento.

## Responsabilidades
Para este projeto o cliente chegou a mim por indicação, então eu fiz entrevista, consultoria, análise de requisitos e por fim orçamento.

Após aprovação do projeto foi dado início a modelagem do sistema e da base de dados, programação do site e configuração do servidor.

Ao longo do projeto foram feitas reuniões de acompanhamento quinzenais com o cliente e reuniões semanais com o designer responsável do projeto.

## Tecnologias
O Cuidado e Afeto foi desenvolvido com o NextJS + Typescript para geração das páginas estáticas, o framework bootstrap e os dados do banco de dados são consumidos no lado do cliente através de uma API feita usando Lumen, um micro-framework baseado em Laravel.

Os diagramas UML foram feitos usando draw.io, o banco de dados usado é o MariaDB, Github Actions para automatização do deploy e o pagseguro como gateway de pagamentos.

## Características
- Pesquisa por dia: Página intuítiva para pesquisar profissionais disponíveis por data.
- Profissionais verificados: Apenas profissionais verificados terão o cadastro aceito, aumentando segurança do cliente.
- Entre em contato diretamente com o profissional após confirmação do pagamento.
- Integração do sistema de pagamento por meio do gateway de pagamentos, principalmente a parte do PIX.

## Desafios encontrados
1. Muito aprendizado em relação a gerenciamento de projeto e do cliente.
1. Como único do desenvolvedor do projeto, foi muito trabalhoso gerenciar repositórios diferentes com tecnologias tão distintas, um sistema que roda no lado do cliente em React e um backend em php.
1. O dashboard de gerenciamento do administrador tomou muito mais tempo de execução do que o previsto, principalmente para implantação dos token JWT para autenticação.
1. O componente para selecionar a data nas telas de exibir os profissionais disponíveis e dentro do perfil do profissional (onde mostra os horários disponíveis do profissional), foi surpreendemente mais difícil que imaginado.

{{< video src="/videos/demonstracao-cuidado-e-afeto.mp4">}}
