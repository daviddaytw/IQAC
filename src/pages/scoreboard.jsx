import {
  TableContainer,
  Table,
  TableHead,
  TableBody,
  TableRow,
  TableCell,
  Paper,
} from '@material-ui/core';
import PropTypes from 'prop-types';
import { useEffect, useState } from 'react';
import Parse from 'parse';

export default function Scoreboard({ contest }) {
  const [data, setData] = useState({});
  const [columns, setColumns] = useState([]);
  const [userMap, setUserMap] = useState({});

  useEffect(async () => {
    const Submission = Parse.Object.extend('Submission');
    const submissionQuery = new Parse.Query(Submission);
    submissionQuery.equalTo('contest', contest);
    submissionQuery.ascending('score');
    submissionQuery.include('submitter');
    submissionQuery.find().then((submissions) => {
      submissions.forEach((submission) => {
        const submitterId = submission.get('submitter').id;
        const questionId = submission.get('question').id;
        userMap[submitterId] = submission.get('submitter').get('username');
        if (!data[submitterId]) data[submitterId] = {};
        data[submitterId][questionId] = submission.get('score');
      });
      setUserMap(userMap);
      setData(data);
    });

    const Question = Parse.Object.extend('Question');
    const questionQuery = new Parse.Query(Question);
    questionQuery.equalTo('contest', contest);
    questionQuery.find().then((questions) => {
      setColumns(questions);
    });
  }, []);

  return (
    <TableContainer component={Paper}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Username</TableCell>
            <TableCell align="right">Total</TableCell>
            {
              columns.map((col) => <TableCell key={col.id} align="right">{col.get('title')}</TableCell>)
            }
          </TableRow>
        </TableHead>
        <TableBody>
          {
            Object.keys(data).map((submitterId) => (
              <TableRow key={submitterId}>
                <TableCell component="th" scope="row">
                  {userMap[submitterId]}
                </TableCell>
                <TableCell align="right">
                  {
                    Object.values(data[submitterId]).reduce((x, y) => (x + y))
                  }
                </TableCell>
                {
                  columns.map(({ id: questionId }) => (
                    <TableCell align="right" key={questionId}>
                      {data[submitterId][questionId]}
                    </TableCell>
                  ))
                }
              </TableRow>
            ))
          }
        </TableBody>
      </Table>
    </TableContainer>
  );
}

Scoreboard.propTypes = {
  contest: PropTypes.shape({
    id: PropTypes.string,
    get: PropTypes.func,
  }),
};

Scoreboard.defaultProps = {
  contest: {},
};
