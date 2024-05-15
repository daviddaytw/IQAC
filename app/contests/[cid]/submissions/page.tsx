"use client";
import { Navbar } from "@/components/Navbar";
import { ScoreCell } from "@/components/ScoreCell";
import { getAuth } from "firebase/auth";
import { DocumentData, collection, doc, getDoc, getDocs, getFirestore, query, where } from "firebase/firestore";
import { useEffect, useState } from "react";
import { Table } from "react-bootstrap";

export default function Page({ params }: { params: { cid: string } }) {
  const [submissions, setSubmissions] = useState<DocumentData[]>([])

  useEffect(() => {
    (async () => {
      const auth = getAuth()

      const db = getFirestore()

      const contestRef = doc(db, 'contests', params.cid)
      const submissionsCol = collection(db, 'participants', auth.currentUser!.uid, 'submissions')
      const submissionsQuery = query(submissionsCol, where('contest', '==', contestRef))
      const submissionsSnaps = await getDocs(submissionsQuery)
      setSubmissions(
        await Promise.all(submissionsSnaps.docs.map(async (d) => {
          return {
            id: d.id,
            ...d.data(),
            question: (await getDoc(d.data().question)).data(),
            ...(await getDoc(doc(db, `${d.ref.path}/private/content`))).data(),
          };
        }))
      )
    })()
  }, [params.cid])

  return (
    <>
      <Navbar>
        <a className="nav-link" aria-current="page" href={`/contests/${params.cid}`}>Problems</a>
        <a className="nav-link" aria-current="page" href={`/contests/${params.cid}/scoreboard`}>Scoreboard</a>
        <a className="nav-link active" aria-current="page" href={`/contests/${params.cid}/submissions`}>Submissions</a>
      </Navbar>
      <main className="container p-4">
        <h1>My Submissions</h1>

        <Table striped>
          <thead>
            <tr>
              <th scope="col">Question</th>
              <th scope="col">Score</th>
              <th scope="col">Answer</th>
              <th scope="col">Comment</th>
              <th scope="col">Submitted Time</th>
            </tr>
          </thead>
          <tbody>
            {
              submissions.map((submission) => (
                <tr key={submission.id}>
                  <td>
                    {submission.question.title}
                  </td>
                  <ScoreCell score={submission.score} />
                  <td>{ submission.answer }</td>
                  <td>{ submission.comment }</td>
                  <td>{ submission.created_at.toDate().toString() }</td>
                </tr>
              ))
            }
          </tbody>
        </Table>
      </main>
    </>
  )
}
