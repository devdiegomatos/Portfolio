---
date: '2026-05-27T21:48:29-03:00'
draft: false
title: 'Prepara Vestibular'
weight: 10

cover:
  image: "prepara-vestibular.png"
  alt: "Projeto Prepara Vestibular"
  caption: "Homepage Prepara Vestibular"
---

Plataforma gratuita de resolução de questões para o vestibular, treine com simulados ou através do banco de questões com gabarito. Uma nova alternativa para ajudar os estudantes do País inteiro a se prepararem para as provas. [Acesse já!](https://www.preparavestibular.com.br)

<!-- more -->

## Introdução
O Projeto [Prepara Vestibular](https://www.preparavestibular.com.br) é um projeto web full-stack de minha propriedade que ainda está em desenvolvimento.

Com o objetivo de prover soluções digitais para alunos estudarem por meio de questões, simulados, pesquisarem matérias e tirar dúvidas com professores. Além de também trazer praticidade para professores na hora de montar suas provas, lista de exercícios e gerar uma fonte de renda extra tirando dúvidas de alunos.

## Responsabilidades
Como o projeto é uma iniciativa minha, fui responsável pela parte de programação do site, modelar o sistema, projetar o banco de dados, configurar o servidor, coordenar os profissionais (contratados) de designer e de marketing digital de acordo com os ideias do projeto.

## Tecnologias
O Prepara Vestibular é construído em cima da stack TALL, sigla que significa Tailwindcss, Alpinejs, Laravel e Livewire.

Os diagrams UML foram feitos usando draw.io, o banco de dados usado é o MariaDB, Github Actions para automatização do deploy e o Stripe como gateway de pagamentos.

## Características
Banco de questões: base de dados que permite realizar consultas complexas, filtrando questões por assuntos gerais, especifícos, prova, ano e dificuldade.
Simulados fluídos e bem responsíveis.
Ajuda de professores: Aluno pode pedir ajuda a um professor dentro da plataforma. Sujeito a disponibilidade.
Testes, testes e mais teste: Apesar de eu não seguir a risca o TDD (Test Driven Development), para este projeto me esforcei para escrever mais de 300 testes.

## Desafios encontrados
- Dificuldade em popular a base de dados em razão da falta de uma api para consultar questões de vestibulares, sendo necessário cadastramento manual apoiado com técnicas de web scraping.
- A integração de um editor de texto para o cadastramento das questões foi bem desafiador, dado que as questões precisam possuir enunciado, metadados, e alternativas (essas que podem variar de quantidade e formato dependendo da prova).
- Montar a tela para realizar simulados foi particularmente bem trabalhosa.
- Uma novidade para mim foi desenvolver um sistema para limitar ações do usuário dependendo cargo que pertence: visitante, aluno não assinante, professor não assinante, aluno assinante e professor assinante.
- A parte de consentimento de usuário para os cookies não foi bem intuitiva.
- Gerenciamento das telas de portais dependendo do cargo do usuário: aluno, professor, administrador...
- SEO e montar as campanhas pagas.

{{< video src="/videos/demonstracao-preparavestibular.mp4">}}
