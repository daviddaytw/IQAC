"use client";
import { Navbar } from "@/components/Navbar";
import { getAuth } from "firebase/auth";
import { DocumentData, addDoc, collection, doc, getDoc, getFirestore, setDoc } from "firebase/firestore";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { Button, Form } from "react-bootstrap";

export default function Page({ params }: { params: { cid: string, pid: string }}) {
  const router = useRouter()
  const [ question, setQuestion ] = useState<DocumentData>({})
  const [ answer, setAnswer ] = useState('')

  useEffect(() => {
    (async () => {
      const db = getFirestore()
      const questionRef = doc(db, 'contests', params.cid, 'questions', params.pid)
      const questionDoc = await getDoc(questionRef)
      setQuestion({id: questionDoc.id, ...questionDoc.data()})
    })()
  }, [params.cid, params.pid])

  const submitAnswer = async () => {
    const db = getFirestore()
    const auth = getAuth()
    const submissionCol = collection(db, 'participants', auth.currentUser!.uid, 'submissions')
    const contestRef = doc(db, 'contests', params.cid)
    const questionRef = doc(db, 'contests', params.cid, 'questions', params.pid)
    const submissionRef = await addDoc(submissionCol, {
      contest: contestRef,
      question: questionRef,
      created_at: new Date()
    })
    await setDoc(doc(db, `${submissionRef.path}/private/content`), {
      answer
    })

    router.push(`/contests/${params.cid}/submissions`)
  }

  return (
    <>
      <Navbar>
        <a className="nav-link" aria-current="page" href={`/contests/${params.cid}`}>Problems</a>
        <a className="nav-link" aria-current="page" href={`/contests/${params.cid}/scoreboard`}>Scoreboard</a>
        <a className="nav-link" aria-current="page" href={`/contests/${params.cid}/submissions`}>Submissions</a>
      </Navbar>
      <main className="container p-5">
        <div className="p-4 bg-secondary-subtle rounded">
            <h1>{question.title}</h1>
            <p>{question.content}</p>
        </div>


        <Form className="p-2">
          <Form.Group className="mb-3">
            <Form.Label>Your Answer</Form.Label>
            <Form.Control as="textarea" rows={4} onChange={(v) => {setAnswer(v.target.value)}} />
          </Form.Group>
          <Button variant="primary" onClick={submitAnswer}>
            Submit
          </Button>
        </Form>
      </main>
    </>
  )
}
