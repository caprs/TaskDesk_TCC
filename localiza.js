  var map;
  var infowindow; //janelas de informações quando clicar nos marcadores do mapa
  var service; //api de lugares maps
  var userLocation; //localizacao do usuario
  var userMarker; //marcador para mostrar a posicao do usuario
  var directionsService; //calcula rota
  var directionsRenderer; //desenha a rota

  function initMap() {
      map = new google.maps.Map(document.getElementById('map'), { //inicia o mapa na div map
          center: { lat: 0, lng: 0 }, //centralizado
          zoom: 15, 
          mapTypeId: 'hybrid', //mapa de satelite e normal juntos
      });

      infowindow = new google.maps.InfoWindow(); 
      service = new google.maps.places.PlacesService(map); // inicia o serviço para buscar coworkings 
      directionsService = new google.maps.DirectionsService(); //inicia o serviço de rotdas
      directionsRenderer = new google.maps.DirectionsRenderer({ //mostrar direções no mapa
          map: map,
          suppressMarkers: true 
      });

      if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition( //pega a localizacao do usuario
              function (position) { //localizacao armazenada em userLocation
                  userLocation = {
                      lat: position.coords.latitude,
                      lng: position.coords.longitude,
                  };

                  map.setCenter(userLocation); //mostra a posição do usuario com o marcador
                  showUserLocation();
              },
              function () {
                  alert("Não foi possível obter sua localização.");
              }
          );
      }

      document.getElementById('buscarLoc').addEventListener('click', function () { //quando o input for clicado, chama a função geocode para achar o endereço inserido
          var address = document.getElementById('inputLoc').value;
          geocodeAddress(address);
      });
  }

  function createDistanceInfoWindow(place, distanceText, durationText) {
    if (!place || !distanceText || !durationText) return;

    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div>
                <strong>${place.name}</strong>
                <p>Distância: ${distanceText}</p>
                <p>Tempo estimado: ${durationText}</p>
            </div>
        `,
        position: place.geometry.location,
    });

    // Exibe o balão no mapa
    infoWindow.open(map);

    // Fecha o balão automaticamente após 5 segundos
    setTimeout(() => {
        infoWindow.close();
    }, 5000);
}


  function showUserLocation() { //mostrar localizacao do usuario no mapa
      if (!userLocation) {
          alert("Localização do usuário não disponível.");
          return;
      }

      userMarker = new google.maps.Marker({ //cria um marcador e coloca onde a posicao do usuario esta
          position: userLocation, //posicao atual do usuario
          map: map,
          title: "Você está aqui!",
          icon: {
              url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png", // icone personalizado
          },
      });

      map.setZoom(14); //zoom
  }

  function geocodeAddress(address) { //converte o endereço em coordenadas
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({ address: address }, function (results, status) {
          if (status === 'OK') {
              var destination = results[0].geometry.location; //se der certo, o mapa é centralizado nos lugares encontrados 
              map.setCenter(destination);
              searchNearbyCoworking(destination); //chamado para procurar os coworking proximos
          } else {
              alert('Geocode falhou: ' + status);
          }
      });
  }

  function searchNearbyCoworking(location) {
      var request = {
          location: location,
          radius: 10000, // Raio em metros
          keyword: 'coworking', //palavra-chave
      };

      service.nearbySearch(request, function (results, status) {
          if (status === google.maps.places.PlacesServiceStatus.OK) { //se os serviços de lugares no mapa derem certo
              const resultadosContainer = document.getElementById('grid-container'); //div para exibir os resultados 
              resultadosContainer.innerHTML = "";

              results.forEach((place) => { //para cada lugar encontrado
                  calculateDistance(place); //chama a funcao para calcular a distancia
                  createMarker(place); //criar o marcador para o lugar
                  addCoworkingToList(place); // adicionar coworking para a lista da div
              });
          } else {
              alert("Nenhum coworking encontrado.");
          }
      });
  }

  function addCoworkingCard(place) {
    const gridContainer = document.getElementById('grid-container'); // Container dos cards

    // Cria um card para cada coworking
    const coworkingCard = document.createElement('div');
    coworkingCard.className = 'coworking-card'; // Classe para estilização no CSS

    coworkingCard.innerHTML = `
        <strong>${place.name}</strong>
        <p>${place.vicinity || "Endereço não disponível"}</p>
        ${place.rating ? `<p>Classificação: ${place.rating}</p>` : ""}
        <button class="rota-btn">Ver Rota</button>
    `;

    // Adiciona um evento ao botão "Ver Rota"
    coworkingCard.querySelector('.rota-btn').addEventListener('click', function () {
        createRouteToCoworking(place);
    });

    // Adiciona o card ao container
    gridContainer.appendChild(coworkingCard);
}


  function createMarker(place) { //criar marcador para cada coworking encontrado
      var marker = new google.maps.Marker({
          map: map,
          position: place.geometry.location,
          title: place.name,
      });

      google.maps.event.addListener(marker, 'click', function () { //ao clicar no marcador, uma janela é exibida com o nome do lugar
          infowindow.setContent(place.name);
          infowindow.open(map, this);
      });

      place.marker = marker;
  }

  function calculateDistance(place) { //calcular distancia do usuario para o lugar
      if (!userLocation) { //se a localizacao do usuario for diferente
          alert("Localização do usuário não disponível.");
          return;
      }

      //variavel para achar a localizacao do usuario com latitude e longitude
      var origin = new google.maps.LatLng(userLocation.lat, userLocation.lng);
      var destination = place.geometry.location; //achar a localizacao do destino

      var distanceService = new google.maps.DistanceMatrixService(); //serviço de distancia API
      distanceService.getDistanceMatrix(
          {
              origins: [origin],
              destinations: [destination],
              travelMode: 'DRIVING', //calcula a distancia baseada no serviço de carro
          },
          function (response, status) {
              if (status === 'OK') {
                  const element = response.rows[0].elements[0];
                  const distanceText = element.distance ? element.distance.text : "Distancia não disponível";
                  const durationText = element.duration ? element.duration.text : "Tempo não disponível";

                  addCoworkingToList(place, distanceText, durationText);
              } else {
                  console.error('Erro ao calcular distância:', status);
              }
          }
      );
  }

  function addCoworkingToList(place, distanceText, durationText) { //adiciona os coworking para a div
      const infoPanel = document.getElementById('resultados'); //div no html
      const coworkingItem = document.createElement('div');//dentro da div
      coworkingItem.className = 'coworking-item'; //cada item terá esse nome

      const content = ` 
          <strong>${place.name}</strong> 
          <p>${place.vicinity || "Endereço não disponível"}</p>
          ${place.rating ? `<p>Classificação: ${place.rating}</p>` : ""}
          
          
      `;
      coworkingItem.innerHTML = content;

      // Quando o usuário clicar no coworking da lista, desenha a rota para ele
      coworkingItem.addEventListener('click', function () {
          createRouteToCoworking(place);
      });

      infoPanel.appendChild(coworkingItem);
  }

  // Função para criar a rota até o coworking específico
  function createRouteToCoworking(place) {
    if (!userLocation) {
        alert("Localização do usuário não disponível.");
        return;
    }

    var request = {
        origin: userLocation,
        destination: place.geometry.location,
        travelMode: 'DRIVING',
    };

    directionsService.route(request, function (response, status) {
        if (status === 'OK') {
            // Renderiza a rota no mapa
            directionsRenderer.setDirections(response);

            // Calcula e exibe a distância no balão
            var distanceService = new google.maps.DistanceMatrixService();
            distanceService.getDistanceMatrix(
                {
                    origins: [userLocation],
                    destinations: [place.geometry.location],
                    travelMode: 'DRIVING',
                },
                function (response, status) {
                    if (status === 'OK') {
                        const element = response.rows[0].elements[0];
                        const distanceText = element.distance ? element.distance.text : "Distância não disponível";
                        const durationText = element.duration ? element.duration.text : "Distância não disponível";


                        // Chama o InfoWindow com a distância
                        createDistanceInfoWindow(place, distanceText, durationText);
                    } else {
                        console.error('Erro ao calcular distância:', status);
                    }
                }
            );
        } else {
            console.error("Erro ao calcular a rota: " + status);
        }
    });
}
