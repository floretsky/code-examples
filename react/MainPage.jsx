import React, { useEffect, useState } from 'react';
import { loginRequest } from '../../authConfig';
import { useMsal } from '@azure/msal-react';

import {
  FormControl,
  InputLabel,
  MenuItem,
  Select,
  Grid,
  Paper,
  Typography,
  Box,
  Card,
  CardHeader,
  CardContent
} from '@mui/material';
import { callMsGraph } from './graph';
import jsonData from '../../mainPageData.json';

export const MainPage = (props) => {
  const { instance, accounts } = useMsal();
  const jsonParsedData = JSON.parse(JSON.stringify(jsonData));
  const [graphData, setGraphData] = useState(null);
  const [currentData, setCurrentData] = useState(0);

  useEffect(() => {
    document.getElementById('root').style.backgroundColor = '#f6fafc';
  }, []);

  const handleCurrentData = (event) => {
    setCurrentData(event.target.value);
  };

  function RequestProfileData() {
    const request = {
      ...loginRequest,
      account: accounts[0]
    };

    // Silently acquires an access token which is then attached to a request for Microsoft Graph data
    instance
      .acquireTokenSilent(request)
      .then((response) => {
        callMsGraph(response.accessToken).then((response) => setGraphData(response));
      })
      .catch((e) => {
        instance.acquireTokenPopup(request).then((response) => {
          callMsGraph(response.accessToken).then((response) => setGraphData(response));
        });
      });
  }
  return (
    <>
      <Grid container spacing={2}>
        <Grid item xs={12} md={6}>
          <Paper sx={{ mb: 3 }} elevation={5}>
            <Box sx={{ p: 3 }}>
              <Grid container spacing={2} justifyContent="center" alignItems="center">
                <Grid item xs={6} sm={4} md={5} style={{ textAlign: 'center' }}>
                  <img
                    src={jsonParsedData[currentData].data.companyInfo.picAddress}
                    alt="company-logo"
                    style={{ width: '-webkit-fill-available', maxWidth: '200px' }}
                  />
                </Grid>
                <Grid item xs={12} sm={8} md={7}>
                  <Typography variant="subtitle1" gutterBottom component="div">
                    <span className="bold">Company name:</span>{' '}
                    {jsonParsedData[currentData].data.companyInfo.companyName}
                  </Typography>
                  <Typography variant="subtitle1" gutterBottom component="div">
                    <span className="bold">N seats:</span>{' '}
                    {jsonParsedData[currentData].data.companyInfo.Nseats}
                  </Typography>
                  <Typography variant="subtitle1" gutterBottom component="div">
                    <span className="bold">N devices:</span>{' '}
                    {jsonParsedData[currentData].data.companyInfo.Ndevices}
                  </Typography>
                  <Typography variant="subtitle1" gutterBottom component="div">
                    <span className="bold">Administrator:</span>{' '}
                    {jsonParsedData[currentData].data.companyInfo.adminName}
                  </Typography>
                </Grid>
              </Grid>
            </Box>
          </Paper>
          {jsonParsedData[currentData].data.latestAlert && (
            <Card sx={{ mb: 3 }}>
              <CardHeader
                title="Latest Alert"
                sx={{ bgcolor: '#fc304e', color: 'white', textTransform: 'uppercase' }}
              ></CardHeader>
              <CardContent>{jsonParsedData[currentData].data.latestAlert}</CardContent>
            </Card>
          )}
        </Grid>
        <Grid item xs={12} md={6}>
          <FormControl fullWidth sx={{ mb: 3 }}>
            <InputLabel id="deployed-solution-label">Deployed solution</InputLabel>
            <Select
              labelId="deployed-solution-label"
              id="deployed-solution-select"
              value={currentData}
              label="Deployed solution"
              onChange={handleCurrentData}
              style={{ backgroundColor: '#fff' }}
            >
              {jsonParsedData.map((currentObj, index) => (
                <MenuItem key={currentObj.title} value={index}>
                  {jsonParsedData[index].title}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
          <Paper
            className="mainPageStats"
            style={{ backgroundColor: '#1a4462', color: '#fff', textAlign: 'center' }}
          >
            <Box sx={{ p: 3 }}>
              <Grid container spacing={2}>
                <Grid item xs={6}>
                  <Typography variant="h4" gutterBottom component="p">
                    {jsonParsedData[currentData].data.tableData.devicesOnline}
                  </Typography>
                  <Typography className="uppercase">Devices Online</Typography>
                </Grid>
                <Grid item xs={6}>
                  <Typography variant="h4" gutterBottom component="p">
                    {jsonParsedData[currentData].data.tableData.modelsRunning}
                  </Typography>
                  <Typography className="uppercase">Models running</Typography>
                </Grid>
                <Grid item xs={6}>
                  <Typography variant="h4" gutterBottom component="p">
                    {jsonParsedData[currentData].data.tableData.averageCPUuse}
                  </Typography>
                  <Typography className="uppercase">Average CPU use</Typography>
                </Grid>
                <Grid item xs={6}>
                  <Typography variant="h4" gutterBottom component="p">
                    {jsonParsedData[currentData].data.tableData.averageMemoryUse}
                  </Typography>
                  <Typography className="uppercase">Average memory use</Typography>
                </Grid>
                <Grid item xs={6}>
                  <Typography variant="h4" gutterBottom component="p">
                    {jsonParsedData[currentData].data.tableData.NdriftedDevices}
                  </Typography>
                  <Typography className="uppercase">N drifted devices</Typography>
                </Grid>
                <Grid item xs={6}>
                  <Typography variant="h4" gutterBottom component="p">
                    {jsonParsedData[currentData].data.tableData.averageModelDriftDelta}
                  </Typography>
                  <Typography className="uppercase">Average model drift delta</Typography>
                </Grid>
                <Grid item xs={6}>
                  <Typography variant="h4" gutterBottom component="p">
                    {jsonParsedData[currentData].data.tableData.averageModelrmse}
                  </Typography>
                  <Typography className="uppercase">Average model rmse</Typography>
                </Grid>
                <Grid item xs={6}>
                  <Typography variant="h4" gutterBottom component="p">
                    {jsonParsedData[currentData].data.tableData.averageSmth}
                  </Typography>
                  <Typography className="uppercase">Average model drift delta</Typography>
                </Grid>
              </Grid>
            </Box>
          </Paper>
        </Grid>
      </Grid>
    </>
  );
};
