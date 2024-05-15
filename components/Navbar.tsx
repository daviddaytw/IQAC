import Image from "next/image";
import { LoginModal } from "./LoginModal";

export function Navbar({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <nav className="navbar navbar-expand-lg bg-body-tertiary">
      <div className="container-fluid">
        <a className="navbar-brand" href="/">
          <Image src="/logo192.png" alt="IQAC" width="30" height="24" />
        </a>
        <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span className="navbar-toggler-icon"></span>
        </button>
        <div className="collapse navbar-collapse" id="navbarSupportedContent">
          <ul className="navbar-nav me-auto mb-2 mb-lg-0">
            { children }
          </ul>
          <LoginModal />
        </div>
      </div>
    </nav>
  )
}