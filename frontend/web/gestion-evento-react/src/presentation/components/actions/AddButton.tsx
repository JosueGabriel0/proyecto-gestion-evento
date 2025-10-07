import styled from "styled-components";

interface AddButtonProps {
  onClick?: () => void; // 👈 permite el callback
}

const AddButton = ({ onClick }: AddButtonProps) => {
  return (
    <StyledWrapper>
      <button className="Btn" onClick={onClick}>
        <div className="sign">+</div>
        <div className="text">Crear</div>
      </button>
    </StyledWrapper>
  );
};

const StyledWrapper = styled.div`
  .Btn {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 45px;
    height: 45px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition-duration: 0.3s;
    box-shadow: 0 0 6px rgba(125, 249, 255, 0.3); /* 👈 glow inicial sutil */
    background-color: black;
  }

  /* plus sign con glow inicial */
  .sign {
    width: 100%;
    font-size: 2em;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    text-shadow: 0 0 4px #7df9ff, 0 0 6px rgba(125, 249, 255, 0.4); /* glow leve */
  }

  /* texto oculto inicialmente */
  .text {
    position: absolute;
    right: 0%;
    width: 0%;
    opacity: 0;
    color: white;
    font-size: 1.2em;
    font-weight: 500;
    transition-duration: 0.3s;
  }

  /* hover sobre el botón */
  .Btn:hover {
    width: 125px;
    border-radius: 20px;
    background-color: #0f2f38;
    box-shadow: 0 0 8px #7df9ff, 0 0 16px #7df9ff; /* glow más intenso */
  }

  .Btn:hover .sign {
    width: 30%;
    padding-left: 20px;
    color: #7df9ff;
    text-shadow: 0 0 6px #7df9ff, 0 0 12px #7df9ff; /* se intensifica */
  }

  .Btn:hover .text {
    opacity: 1;
    width: 70%;
    padding-right: 20px;
    color: #7df9ff;
    text-shadow: 0 0 6px #7df9ff;
  }

  /* efecto click */
  .Btn:active {
    transform: translate(2px, 2px);
  }
`;

export default AddButton;