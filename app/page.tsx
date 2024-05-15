"use client";
import { useEffect, useState } from 'react';
import { DocumentData, collection, getDocs, getFirestore } from 'firebase/firestore';
import Link from 'next/link';
import { Navbar } from '@/components/Navbar'

export default function Home() {

  const [contests, setContests] = useState<DocumentData[]>([])

  useEffect(() => {
    const db = getFirestore()
    const contestsCol = collection(db, 'contests')
    getDocs(contestsCol)
      .then(contestsSnapshots => {
        setContests(contestsSnapshots.docs.map(doc => ({id: doc.id, ...doc.data()})))
      })
  }, [])

  return (
    <div>
      <Navbar>
        <li className="nav-item">
          <a className="nav-link active" aria-current="page" href="/">Contests</a>
        </li>
      </Navbar>
      <main className="container p-4">
        <h1>Contests</h1>
        <table className="table table-striped">
          <thead>
            <tr>
              <th scope="col">Contest</th>
              <th scope="col">Begin</th>
              <th scope="col">Finish</th>
            </tr>
          </thead>
          <tbody>
            {
              contests.map((contest) => (
                <tr key={contest.id}>
                  <td>
                    <Link href={`/contests/${contest.id}`}>
                      { contest.name }
                    </Link>
                  </td>
                  <td>{ contest.begin.toDate().toString() }</td>
                  <td>{ contest.finish.toDate().toString() }</td>
                </tr>
              ))
            }
          </tbody>
        </table>
      </main>
    </div>
  );
}
