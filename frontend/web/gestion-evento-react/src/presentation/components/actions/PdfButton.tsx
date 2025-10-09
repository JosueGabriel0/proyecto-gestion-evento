import { faFilePdf } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import styled from "styled-components";

const PdfButton = () => {
  const handleClick = () => {
    alert("Botón PDF presionado 🚀");
  };

  return (
    <StyledWrapper>
      <button className="container-btn-file" onClick={handleClick}>
        <FontAwesomeIcon
          icon={faFilePdf}
          style={{ color: "#ffffff", marginRight: "0.5em" }}
          size="lg"  // 👈 un poco más grande
        />
        PDF
      </button>
    </StyledWrapper>
  );
};

const StyledWrapper = styled.div`
  .container-btn-file {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #c0392b;
    color: #fff;
    border: none;
    padding: 0.6em 1.2em; /* 👈 más espacio que la versión compacta */
    font-size: 0.95rem;   /* 👈 un poco más grande que 0.85rem */
    border-radius: 0.45em;
    cursor: pointer;
    box-shadow: 2px 5px 8px -2px rgba(0, 0, 0, 0.3);
    transition: all 250ms;
    position: relative;
    overflow: hidden;
    z-index: 1;
  }

  .container-btn-file::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 0;
    border-radius: 0.45em;
    background-color: #e74c3c;
    z-index: -1;
    transition: all 350ms;
  }

  .container-btn-file:hover::before {
    width: 100%;
  }
`;

export default PdfButton;