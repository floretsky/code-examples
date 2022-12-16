import React, { useEffect, useState } from 'react';
import { loginRequest } from '../../authConfig';
import { NavBar } from '../NavBar/NavBar';
import { useMsal } from '@azure/msal-react';

import { callForPic } from './graph';
import { Copyright } from '../Copyright/Copyright';

import { MainPage } from './MainPage';
import { SystemHealth } from '../GraphsPages/SystemHealth';
import { MlOps } from '../GraphsPages/MlOps';
import { DataExplorer } from '../GraphsPages/DataExplorer';
import { PageNotFound } from '../NotFound/PageNotFound';
import { Container } from '@mui/material';

import { BrowserRouter, Route, Routes } from 'react-router-dom';

export const ProfileContent = (props) => {
  const { instance, accounts } = useMsal();
  const [image, setImage] = useState();

  const name = accounts[0] && accounts[0].name;

  useEffect(() => {
    const request = {
      ...loginRequest,
      account: accounts[0]
    };

    // Silently acquires an access token which is then attached to a request for Microsoft Graph data
    instance
      .acquireTokenSilent(request)
      .then((response) => {
        callForPic(response.accessToken).then((response) => {
          const reader = new FileReader();
          reader.readAsDataURL(response);

          reader.onload = () => {
            const base64data = reader.result;
            setImage(base64data);
          };
        });
      })
      .catch((e) => {
        instance.acquireTokenPopup(request).then((response) => {
          callForPic(response.accessToken).then((response) => setImage(response));
        });
      });
  }, []);

  return (
    <>
      <BrowserRouter>
        <NavBar avatar={image} name={name} />
        <Container sx={{ mt: 3, pb: 5 }} maxWidth="xl">
          <Routes>
            <Route path="/" element={<MainPage />} />
            <Route path="system-health" element={<SystemHealth />} />
            <Route path="ml-ops" element={<MlOps />} />
            <Route path="data-explorer" element={<DataExplorer />} />
            <Route path="404" element={<PageNotFound />} />
            <Route path="*" element={<PageNotFound />} />
          </Routes>
        </Container>
      </BrowserRouter>
      <Copyright color="black" />
    </>
  );
};
