---
date: '2026-06-28T16:43:29-03:00'
draft: true
title: ''
---

Recentemente comecei a trabalhar na Tecgraf e me deram um problema interessante "queremos selecionar todos os elementos que estão contidos num bloco de anotação". Estou num projeto grande de automação e controle que envolve muitas partes complexas, uma delas é a IDE que os engenheiros usam linguagem visual para programar os processos industriais. Deseja-se que todos elementos contidos (inteiramente ou parcialmente) no bloco de anotação sejam associados implicitamente. Como fazer isso?

## Introdução
O bloco de anotação é um polígono representado como uma sequência de vértices ordenados, já o os outros elementos dependem do visual do seu bloco, temos: retângulo, círculo, diamante e reta.

Este é um problema geométrico interessante e não me parece muito diferente de determinar se dois objetos estão se intersectando.  Como o programa trabalha em 2 dimensões, me parece bem adequado usar o teorema do eixo separador (SAT). Porém, o bloco de anotação é um polígono genérico que pode ser concavo, convexo ou irregular, já o SAT funciona muito bem para polígonos convexos e creio que terá problema para objetos que estejam inteiramente contidos no polígono, que é o caso mais comum. Logo, esta solução exigiria um passo anterior para decompor o polígono em regiões convexas menores usando algoritmos como *Ear Clipping*.

Usar um algoritmo de decomposição me parece muito exagerado para o nosso caso. Pesquisando um pouco mais encontrei que determinar se um dos vértices do objeto está contido no polígono seria suficiente para dizer se o objeto intersecta o polígono. O que faz todo sentido, podemos pensar em 4 configurações possíveis:

1. Vértice-Vértice: este é um caso raro e não estamos muito interessado nele. O vértice do objeto precisa estar exatamente em cima do polígono. Sensível a representação numérica e aos objetos estarem perfeitamente alinhados.
1. Vértice-Aresta: já seria um caso comum e queremos que o objeto seja selecionado 
1. Aresta-Aresta: outro caso raro que também não estamos interessado. Os objetos precisam estar perfeitamente alinhados.
1. Inteiramente contido: no nosso contexto esse seria o caso mais comum

Para os casos que estamos interessados basta que um vértice esteja contido para considerarmos o objetos como selecionado. Dessa forma, reduzimos para um problema de ponto-no-polígono, ou seja, para cada vértice basta testarmos se ele está contido no polígono.

## Problema Ponto-no-Polígono

O teste de ponto-no-polígono é um problema geométrico fundamental 
Neste texto escrevo um algoritmo para resolver um interessante problema de determinar se um ponto está contido em um polígono qualquer, seja ele convexo, concavo ou com autointerseção. Tem aplicações

## Algoritmo do número de voltas para teste de ponto em polígono

## Limitações

## Otimizações
