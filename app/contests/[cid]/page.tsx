"use client";
import { Navbar } from "@/components/Navbar";
import { ScoreCell } from "@/components/ScoreCell";
import { getAuth } from "firebase/auth";
import { DocumentData, collection, doc, getCountFromServer, getDoc, getDocs, getFirestore, query, where } from "firebase/firestore";
import Link from "next/link";
import { useEffect, useState } from "react";

export default function Page({ params }: { params: { cid: string }}) {
  const [ contest, setContest ] = useState<DocumentData>({})
  const [ questions, setQuestions ] = useState<DocumentData[]>([])

  useEffect(() => {
    (async () => {
      const db = getFirestore()
      const contestRef = doc(db, 'contests', params.cid)
      const contestDoc = await getDoc(contestRef)
      setContest({id: contestDoc.id, ...contestDoc.data()})
      
      const auth = getAuth()
      const questionssCol = collection(db, 'contests', params.cid, 'questions')
      const questionsSnapshots = await getDocs(questionssCol)
      setQuestions(await Promise.all(
          questionsSnapshots.docs.map(async doc => {
            const coll = collection(db, 'contests', params.cid,  'submissions')
            const q = query(coll,  where('question', '==', doc.ref))

            return {
              id: doc.id,
              ...doc.data(),
              score: (await getDocs(q)).docs.reduce((prev, doc) => (
                doc.data().uid === auth.currentUser?.uid ? 
                  Math.max(prev, doc.data().score) : prev
              ), 0),
              numSubmissions: (await getCountFromServer(q)).data().count,
            }
          })
        )
      )
    })()
  }, [params.cid])

  return (
    <>
      <Navbar>
        <a className="nav-link active" aria-current="page" href={`/contests/${params.cid}`}>Problems</a>
        <a className="nav-link" aria-current="page" href={`/contests/${params.cid}/scoreboard`}>Scoreboard</a>
        <a className="nav-link" aria-current="page" href={`/contests/${params.cid}/submissions`}>Submissions</a>
      </Navbar>
      <main className="container p-4">
        <h1>{contest.name}</h1>

        <div className="p-4">
          <table className="table table-striped">
            <thead>
              <tr>
                <th scope="col">Question</th>
                <th scope="col">Score</th>
                <th scope="col">Submissions</th>
              </tr>
            </thead>
            <tbody>
              {
                questions.map((question) => (
                  <tr key={question.id}>
                    <td>
                      <Link href={`/contests/${contest.id}/${question.id}`}>
                        { question.title }
                      </Link>
                    </td>
                    <ScoreCell score={question.score} />
                    <td>{ question.numSubmissions }</td>
                  </tr>
                ))
              }
            </tbody>
          </table>
        </div>
      </main>
    </>
  )
}
