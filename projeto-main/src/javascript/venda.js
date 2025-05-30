document.addEventListener("DOMContentLoaded", () => {
  carregarProdutos();
  document
    .getElementById("finalizar-venda")
    .addEventListener("click", finalizarVenda); // Adiciona evento ao botão de finalizar venda

  document
    .getElementById("searchInput")
    .addEventListener("input", filtrarProdutos); // Adiciona evento para o campo de pesquisa
});

let produtos = [];
let carrinho = [];

// Função para carregar os produtos do servidor
async function carregarProdutos() {
  try {
    const response = await fetch("../venda/produtos.php"); // Faz a requisição para carregar os produtos
    produtos = await response.json(); // Converte a resposta em JSON e armazena na variável produtos
    renderizarProdutos(produtos);
  } catch (error) {
    console.error("Erro ao carregar os produtos:", error);
  }
}

// Função para renderizar os produtos na tela
function renderizarProdutos(produtosFiltrados) {
  const produtosContainer = document.getElementById("produtos-container");
  produtosContainer.innerHTML = ""; // Limpa os produtos existentes antes de renderizar novamente

  produtosFiltrados.forEach((produto) => {
    const card = document.createElement("div"); // Cria um card para cada produto
    card.className = "card"; // Atribui uma classe CSS ao card
    card.innerHTML = `
      <img src="${
        produto.imagem ? `../src/${produto.imagem}` : "../uploads/default.jpg"
      }" alt="${produto.nome}">
      <h3>${produto.nome}</h3>
      <p>Cor: ${produto.cor}</p>
      <p>Gênero: ${produto.genero}</p>
      <p>Tamanho: ${produto.tamanho}</p>
      <p>Quantidade: ${produto.quantidade}</p>
      <p class="product-price">Preço: R$${produto.preco}</p>
      <button onclick="adicionarAoCarrinho(${produto.id}, '${produto.nome}', ${
      produto.preco
    })">Adicionar</button>
    `;
    produtosContainer.appendChild(card); // Adiciona o card ao container de produtos
  });
}

// Função para filtrar os produtos com base no texto digitado
function filtrarProdutos() {
  const searchTerm = document.getElementById("searchInput").value.toLowerCase();
  const produtosFiltrados = produtos.filter((produto) => {
    return produto.nome.toLowerCase().includes(searchTerm); // Filtra os produtos pelo nome
  });
  renderizarProdutos(produtosFiltrados); // Renderiza os produtos filtrados
}

// Função para adicionar um produto ao carrinho
function adicionarAoCarrinho(id, nome, preco) {
  const itemExistente = carrinho.find((item) => item.id === id); // Verifica se o produto já existe no carrinho
  if (itemExistente) {
    itemExistente.quantidade++; // Se já estiver no carrinho, apenas aumenta a quantidade
  } else {
    carrinho.push({ id, nome, preco, quantidade: 1 }); // Caso contrário, adiciona um novo item ao carrinho
  }
  atualizarCarrinho();
  atualizarNotificacaoCarrinho(); // Atualiza a notificação de carrinho
}

// Função para remover um produto do carrinho por unidade
function removerDoCarrinho(id) {
  const itemExistente = carrinho.find((item) => item.id === id); // Encontrar o item no carrinho

  if (itemExistente) {
    if (itemExistente.quantidade > 1) {
      itemExistente.quantidade--;
    } else {
      carrinho = carrinho.filter((item) => item.id !== id);
    }
  }

  atualizarCarrinho(); // Atualiza a visualização do carrinho
  atualizarNotificacaoCarrinho(); // Atualiza a notificação do carrinho
}

// Função para atualizar o conteúdo do carrinho e o total
function atualizarCarrinho() {
  const carrinhoItens = document.getElementById("carrinho-itens");
  const total = document.getElementById("total");
  carrinhoItens.innerHTML = ""; // Limpa o carrinho antes de renderizar novamente

  let totalValor = 0; // Variável para acumular o valor total da venda
  carrinho.forEach((item) => {
    totalValor += item.preco * item.quantidade; // Atualiza o valor total da venda
    const itemDiv = document.createElement("div");
    itemDiv.className = "item"; // Cria um item no carrinho
    itemDiv.innerHTML = `
      <span>${item.nome} (x${item.quantidade}) - R$${(
      item.preco * item.quantidade
    ).toFixed(2)}</span>
      <button onclick="removerDoCarrinho(${item.id})">Remover</button>
    `;
    carrinhoItens.appendChild(itemDiv); // Adiciona o item ao carrinho
  });
  total.innerText = totalValor.toFixed(2); // Exibe o valor total da venda na tela
}

// Função para atualizar a notificação do carrinho (badge)
function atualizarNotificacaoCarrinho() {
  const notificacao = document.getElementById("cart-notification");
  const quantidadeItens = carrinho.reduce(
    (total, item) => total + item.quantidade,
    0
  ); // Conta a quantidade total de itens no carrinho

  // Se houver itens no carrinho, exibe o badge com o número de itens
  if (quantidadeItens > 0) {
    notificacao.style.display = "inline-block";
    notificacao.textContent = quantidadeItens; // Atualiza o número de itens no badge
  } else {
    // Se não houver itens, esconde o badge
    notificacao.style.display = "none";
  }
}

// Função para finalizar a venda
async function finalizarVenda() {
  const cpfCliente = document.getElementById("cpfCliente").value;
  const nomeCliente = document.getElementById("nomeCliente").value;
  const formaPagamento = document.getElementById("formaPagamento").value;
  const funcionario = document.getElementById("usuarioLogado").value;
  const dataVenda = document.getElementById("dataAtual").value;

  // Verifica se todos os campos obrigatórios estão preenchidos
  if (
    !cpfCliente ||
    !nomeCliente ||
    !formaPagamento ||
    !funcionario ||
    carrinho.length === 0
  ) {
    alert(
      "Por favor, preencha todos os campos obrigatórios e adicione produtos ao carrinho."
    );
    return;
  }

  // Cria um array com os itens do carrinho (somente ID e preço)
  const itensCarrinho = carrinho.map((item) => ({
    produtoId: item.id,
    precoVenda: item.preco,
    quantidade: item.quantidade,
  }));

  // Cria um objeto com todos os dados da venda
  const dadosVenda = {
    cpfCliente,
    nomeCliente,
    formaPagamento,
    funcionario,
    dataVenda,
    itens: itensCarrinho,
  };

  try {
    // Envia os dados da venda para o servidor via API
    const response = await fetch("../venda/processar-venda.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(dadosVenda), // Envia os dados como um JSON
    });

    const result = await response.json(); // Converte a resposta do servidor em JSON

    if (result.sucesso) {
      alert("Venda realizada com sucesso!");
      // Limpa o carrinho e os campos após finalizar a venda
      carrinho = [];
      atualizarCarrinho();
      document.getElementById("cpfCliente").value = "";
      document.getElementById("nomeCliente").value = "";
      document.getElementById("formaPagamento").value = "";

      location.reload(); // Recarrega a página
    } else {
      // Exibe mensagem de erro se algo der errado
      alert("Erro ao processar a venda: " + result.mensagem);
    }
  } catch (error) {
    console.error("Erro ao enviar os dados da venda:", error); // Exibe erro no console se houver problema na requisição
    alert("Erro ao tentar registrar a venda.");
  }
}
