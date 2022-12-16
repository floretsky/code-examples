import React, { useEffect, useState } from 'react';
import { grafanaApiGetDashboard } from '../ProfileContent/graph';
import {
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  Grid,
  Stack,
  TextField,
  Button
} from '@mui/material';

import { DateTimePicker } from '@mui/x-date-pickers/DateTimePicker';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';

import { grafanaConfig } from '../../authConfig';
import { dashboardUrl, timeStamps } from './grafanaData';

export const DataExplorer = (props) => {
  const [modelsArray, setModelsArray] = useState([]);
  const [currentModel, setCurrentModel] = useState('');

  const [panelsArray, setPanelsArray] = useState([]);
  const [currentPanel, setCurrentPanel] = useState('');

  const [update, updateIframe] = useState(0);
  const [currentTimeStamp, updateTimeStamp] = useState(timeStamps.last6h);

  const [isCustom, setCustom] = useState(0);
  const [customTimeStamp, setCustomTimeStamp] = useState({
    custom: { name: 'Custom time stamp', times: ['now-6h', 'now'], custom: 1 }
  });

  const [customFromValue, setCustomFrom] = useState('');
  const [customToValue, setCustomTo] = useState(new Date().toLocaleString());

  const currentTime = new Date();

  useEffect(() => {
    document.getElementById('root').style.backgroundColor = 'rgb(244, 245, 245)';

    const dateOffset = 60 * 60 * 1000 * 6; //6 hours
    const fromMinus6h = currentTime;
    fromMinus6h.setTime(fromMinus6h.getTime() - dateOffset);
    setCustomFrom(fromMinus6h.toLocaleString());

    grafanaApiGetDashboard(grafanaConfig.grafanaDatasources).then((response) => {
      const responseDatasources = response;
      grafanaApiGetDashboard(grafanaConfig.grafanaDataExplorer).then((response_second) => {
        const panelsArray = response_second.dashboard.panels.filter((el) => el.type !== 'row');
        setPanelsArray(panelsArray);
        setCurrentPanel(panelsArray[0]);

        const variable = response_second.dashboard.templating.list[0].query;
        const arrayOfModels = responseDatasources
          .filter((v) => v.type == variable)
          .map((x) => x.name);
        setModelsArray(arrayOfModels);
        setCurrentModel(arrayOfModels[arrayOfModels.length - 1]);
      });
    });
  }, []);

  const handleCurrentModelChange = (event) => {
    setCurrentModel(event.target.value);
    updateIframe(update + 1);
  };

  const handleCurrentPanelChange = (event) => {
    setCurrentPanel(event.target.value);
    updateIframe(update + 1);
  };

  const handleTimeStampChange = (event) => {
    if (event.target.value.custom == 1) {
      updateTimeStamp(event.target.value);
      setCustom(1);
    } else {
      setCustom(0);
      updateTimeStamp(event.target.value);
      updateIframe(update + 1);
    }
  };

  const handleSetCustomFrom = (newValue) => {
    setCustomFrom(newValue);
  };

  const handleSetCustomTo = (newValue) => {
    setCustomTo(newValue);
  };

  const applyTimestamp = () => {
    const newFrom = new Date(customFromValue).getTime();
    const newTo = new Date(customToValue).getTime();
    customTimeStamp.custom.times = [newFrom, newTo];
    console.log(customTimeStamp);
    setCustomTimeStamp(customTimeStamp);
    updateTimeStamp(customTimeStamp.custom);
    updateIframe(update + 1);
  };

  return (
    <>
      <Grid container spacing={2} justifyContent="left">
        <Grid item xs={12} md={4}>
          <FormControl fullWidth sx={{ mb: 2, minWidth: 80 }}>
            <InputLabel id="models-select-label">Datasource</InputLabel>
            <Select
              labelId="models-select-label"
              id="models-select"
              value={currentModel}
              label="Datasource"
              autoWidth
              onChange={handleCurrentModelChange}
              style={{ backgroundColor: '#fff' }}
            >
              {modelsArray.map((currentObj, index) => (
                <MenuItem key={currentObj} value={currentObj}>
                  {modelsArray[index]}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        </Grid>
        <Grid item xs={12} md={4}>
          <FormControl fullWidth sx={{ mb: 2, minWidth: 80 }}>
            <InputLabel id="panels-select-label">Panel</InputLabel>
            <Select
              labelId="panels-select-label"
              id="panels-select"
              value={currentPanel}
              label="Panel"
              autoWidth
              onChange={handleCurrentPanelChange}
              style={{ backgroundColor: '#fff' }}
            >
              {panelsArray.map((currentObj, index) => (
                <MenuItem key={currentObj} value={currentObj}>
                  {panelsArray[index].title}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        </Grid>
        {currentPanel.type == 'timeseries' && (
          <>
            <Grid item xs={12} md={4}>
              <FormControl fullWidth sx={{ mb: 2, minWidth: 80 }}>
                <InputLabel id="time-select-label">Time Range</InputLabel>
                <Select
                  labelId="time-select-label"
                  id="time-select"
                  value={currentTimeStamp}
                  label="Time Range"
                  autoWidth
                  onChange={handleTimeStampChange}
                  style={{ backgroundColor: '#fff' }}
                >
                  <MenuItem value={customTimeStamp.custom}>{customTimeStamp.custom.name}</MenuItem>
                  {Object.keys(timeStamps).map((key, index) => (
                    <MenuItem key={key} value={timeStamps[key]}>
                      {timeStamps[key].name}
                    </MenuItem>
                  ))}
                </Select>
              </FormControl>
            </Grid>
          </>
        )}
        {isCustom == 1 && currentPanel.type == 'timeseries' && (
          <>
            <Grid item xs={6} sx={{ mb: 1 }}>
              <LocalizationProvider dateAdapter={AdapterDateFns}>
                <Stack spacing={3} style={{ backgroundColor: '#fff' }}>
                  <DateTimePicker
                    label="From"
                    maxDate={currentTime}
                    maxTime={currentTime}
                    value={customFromValue}
                    onChange={handleSetCustomFrom}
                    renderInput={(params) => <TextField {...params} />}
                  />
                </Stack>
              </LocalizationProvider>
            </Grid>
            <Grid item xs={6} sx={{ mb: 1 }}>
              <LocalizationProvider dateAdapter={AdapterDateFns}>
                <Stack spacing={3} style={{ backgroundColor: '#fff' }}>
                  <DateTimePicker
                    label="To"
                    minDate={new Date(customFromValue)}
                    minTime={new Date(customFromValue)}
                    maxDate={currentTime}
                    maxTime={currentTime}
                    value={customToValue}
                    onChange={handleSetCustomTo}
                    renderInput={(params) => <TextField {...params} />}
                  />
                </Stack>
              </LocalizationProvider>
            </Grid>
            <Grid item xs={12} sx={{ mb: 3 }} style={{ textAlign: 'right' }}>
              <Button
                size="large"
                color="primary"
                variant="contained"
                onClick={() => {
                  applyTimestamp();
                }}
              >
                Apply time range
              </Button>
            </Grid>
          </>
        )}
      </Grid>
      <Grid container>
        <Grid item xs={12} md={12}>
          <iframe
            src={`${dashboardUrl.dataExplorer}${currentModel}&from=${currentTimeStamp.times[0]}&to=${currentTimeStamp.times[1]}&viewPanel=${currentPanel.id}`}
            width="100%"
            height="550"
            frameBorder="0"
          ></iframe>
        </Grid>
      </Grid>
    </>
  );
};
