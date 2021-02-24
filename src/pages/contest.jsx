import { useEffect, useState } from 'react';
import {
  AppBar, Box, Button, Container, Tabs, Tab, TextField, Toolbar, Typography,
} from '@material-ui/core';
import Parse from 'parse';
import { useParams } from 'react-router-dom';
import PropTypes from 'prop-types';
import ScoreBoard from './scoreboard';
import Questions from './questions';
import Submissions from './submissions';

function TabPanel({ children, value, index }) {
  return (
    <div
      role="tabpanel"
      hidden={value !== index}
    >
      {value === index && (
      <Box p={3}>
        <Typography>{children}</Typography>
      </Box>
      )}
    </div>
  );
}

TabPanel.propTypes = {
  children: PropTypes.element,
  value: PropTypes.number,
  index: PropTypes.number,
};
TabPanel.defaultProps = {
  children: <div />,
  value: 0,
  index: 0,
};

export default function ContestView() {
  const [value, setValue] = useState(0);
  const [contest, setContest] = useState();

  const { id } = useParams();
  useEffect(() => {
    const Contest = Parse.Object.extend('Contest');
    const query = new Parse.Query(Contest);
    query.get(id)
      .then((result) => {
        setContest(result);
      });
  }, []);

  const handleChange = (event, newValue) => {
    setValue(newValue);
  };

  const handleUpdate = () => {
    contest.set('beginAt', new Date(document.getElementById('beginAt').value));
    contest.set('endAt', new Date(document.getElementById('endAt').value));
    contest.save();
  };

  return (
    contest ? (
      <Container>
        <Toolbar>
          <Typography variant="h5" id="tableTitle" component="div">
            { contest.get('name') }
          </Typography>

          { contest.getACL().getWriteAccess(Parse.User.current()) && (
            <>
              <TextField
                id="beginAt"
                label="Begin"
                type="datetime-local"
                defaultValue={contest.get('beginAt') && contest.get('beginAt').toISOString().slice(0, -1)}
                InputLabelProps={{
                  shrink: true,
                }}
              />
              <TextField
                id="endAt"
                label="End"
                type="datetime-local"
                defaultValue={contest.get('endAt') && contest.get('endAt').toISOString().slice(0, -1)}
                InputLabelProps={{
                  shrink: true,
                }}
              />
            </>
          )}
          <Button variant="contained" color="primary" onClick={handleUpdate}>Update</Button>
        </Toolbar>
        <AppBar position="static">
          <Tabs value={value} onChange={handleChange}>
            <Tab label="Scoreboard" />
            <Tab label="Questions" />
            <Tab label="Submissions" />
          </Tabs>
        </AppBar>
        <TabPanel value={value} index={0}>
          <ScoreBoard contest={contest} />
        </TabPanel>
        <TabPanel value={value} index={1}>
          <Questions contest={contest} />
        </TabPanel>
        <TabPanel value={value} index={2}>
          <Submissions contest={contest} />
        </TabPanel>
      </Container>
    ) : <p>Loading</p>
  );
}
