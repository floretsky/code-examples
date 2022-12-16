import { graphConfig, grafanaConfig, grafanaBearer } from '../../authConfig';

export async function callMsGraph(accessToken) {
  const headers = new Headers();
  const bearer = `Bearer ${accessToken}`;

  headers.append('Authorization', bearer);

  const options = {
    method: 'GET',
    headers: headers
  };

  return fetch(graphConfig.graphMeEndpoint, options)
    .then((response) => response.json())
    .catch((error) => console.log(error));
}

export async function callForPic(accessToken) {
  const headers = new Headers();
  const bearer = `Bearer ${accessToken}`;

  headers.append('Authorization', bearer);
  headers.append('Content-type', 'image/jpeg');

  const options = {
    method: 'GET',
    headers: headers
  };

  return fetch(graphConfig.graphPicEndpoint, options)
    .then((response) => response.blob())
    .catch((error) => console.log(error));
}

export async function grafanaApiGetDashboard(url) {
  const headers = new Headers();
  const bearer = grafanaBearer.auth;

  headers.append('Authorization', bearer);
  headers.append('Content-type', 'application/json');

  const options = {
    method: 'GET',
    headers: headers
  };

  return fetch(url, options)
    .then((response) => response.json())
    .catch((error) => console.log(error));
}
