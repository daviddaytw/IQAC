"use client";
import { useEffect, useState } from 'react';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import { getAuth, signInWithPopup, GoogleAuthProvider, signInAnonymously, User } from "firebase/auth";
import { useRouter } from 'next/navigation';

export function LoginModal() {
  const router = useRouter()
  const [user, setUser] = useState<User | null>(null);

  const [show, setShow] = useState(false);
  const handleShow = () => { setShow(true) }
  const handleClose = () => { setShow(false) }

  useEffect(() => {
    const auth = getAuth()
    setUser(auth.currentUser)
  }, [])

  if( user !== null ) {
    const logout = () => {
      const auth = getAuth()
      auth.signOut().then(router.refresh)
    }

    return (
      <Button variant="secondary" onClick={logout}>
        Logout
      </Button>
    );

  } else {
    const loginWithAnonymous = () => {
      const auth = getAuth();
      signInAnonymously(auth)
        .then(() => {
          router.refresh()
        })
        .catch((error) => {
          console.error(error)
        });
    }
  
    const loginWithGoogle = () => {
      const auth = getAuth();
      const provider = new GoogleAuthProvider();
  
      signInWithPopup(auth, provider)
        .then((result) => {
          // This gives you a Google Access Token. You can use it to access the Google API.
          const credential = GoogleAuthProvider.credentialFromResult(result);
          if( credential !== null) {
            router.refresh()
          }
        }).catch((error) => {
          console.error(error)
        });
      handleClose()
    }

    return (
      <>
        <Button variant="primary" onClick={handleShow}>
          Login
        </Button>

        <Modal show={show} onHide={handleClose}>
          <Modal.Header closeButton>
            <Modal.Title>Login</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <Button variant='secondary' onClick={loginWithAnonymous}>Anonymous Login</Button>
          </Modal.Body>
        </Modal>
      </>
    );
  }
}
