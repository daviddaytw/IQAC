"use client";
import { Navbar } from "@/components/Navbar";
import { ScoreCell } from "@/components/ScoreCell";
import { DocumentData, collection, collectionGroup, doc, getDoc, getDocs, getFirestore, limit, orderBy, query, where } from "firebase/firestore";
import Link from "next/link";
import { useEffect, useState } from "react";
import { Table } from "react-bootstrap";

export default function Page({ params }: { params: { cid: string }}) {
  const [ contest, setContest ] = useState<DocumentData>({})
  const [ questions, setQuestions ] = useState<DocumentData[]>([])
  const [ participants, setParticipants ] = useState<DocumentData[]>([])

  useEffect(() => {
    (async () => {
      const db = getFirestore()
      const contestRef = doc(db, 'contests', params.cid)
      const contestDoc = await getDoc(contestRef)
      setContest({id: contestDoc.id, ...contestDoc.data()})
      
      const questionssCol = collection(db, 'contests', params.cid, 'questions')
      const questionsSnapshots = await getDocs(questionssCol)
      setQuestions(questionsSnapshots.docs.map(doc => ({id: doc.id, ...doc.data()})))

      const participantsCol = collection(db, 'contests', params.cid, 'participants')
      const participantsQuery = query(participantsCol, orderBy('score'))
      const participantsSnapshots = await getDocs(participantsQuery)
      setParticipants(
        await Promise.all(participantsSnapshots.docs.map(async participant => {
          const participantRef = doc(db, 'participants', participant.id)
          const paritcipantDoc = await getDoc(participantRef)
          return {
            name: paritcipantDoc.data()?.name,
            score: paritcipantDoc.data()?.score,
            questions: await Promise.all(
              questionsSnapshots.docs.map(async doc => {
                const submissionQuery = query(collection(db, `${participantRef.path}/submissions`), where('question', '==', doc.ref), orderBy('score', 'desc'), limit(1)) 
                return (await getDocs(submissionQuery)).docs[0].data().score
            }))
          }
        })
      ))
    })()
  }, [params.cid])

  return (
    <>
      <Navbar>
        <a className="nav-link" aria-current="page" href={`/contests/${params.cid}`}>Problems</a>
        <a className="nav-link active" aria-current="page" href={`/contests/${params.cid}/scoreboard`}>Scoreboard</a>
        <a className="nav-link" aria-current="page" href={`/contests/${params.cid}/submissions`}>Submissions</a>
      </Navbar>
      <main className="container p-4">
        <h1>{contest.name} Scoreboard</h1>

        <Table striped bordered hover>
            <thead>
                <tr>
                <th scope="col">Participant</th>
                {
                    questions.map((question) => (
                        <th scope="col" key={question.id}>{question.title}</th>
                    ))
                }
                <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                {
                  participants.map((participant) => (
                    <tr key={participant.id}>
                      <td>{participant.id}</td>
                      {
                        participant.questions.map((score: number, idx: number) => (
                          <ScoreCell key={participant.id + idx} score={score} />
                        ))
                      }
                      <td>{ participant.score }</td>
                    </tr>
                  ))
                }
            </tbody>
        </Table>
      </main>
    </>
  )
}
