import React, { useState } from "react";
import styled from "styled-components";

interface AccordeonProps {
  label?: string;
  options: string[];
  value: string;
  onChange: (value: string) => void;
  placeholder?: string;
  width?: string;
}

const Accordeon: React.FC<AccordeonProps> = ({
  label,
  options,
  value,
  onChange,
  placeholder = "Selecciona una opción",
  width = "250px",
}) => {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <StyledWrapper width={width}>
      <div className="brutalist-container">
        <button
          type="button"
          className="brutalist-input smooth-type trigger"
          onClick={() => setIsOpen(!isOpen)}
        >
          {value || placeholder}
          <span className="arrow">{isOpen ? "▲" : "▼"}</span>
        </button>
        {label && <label className="brutalist-label">{label}</label>}

        {isOpen && (
          <ul className="list">
            {options.map((option) => (
              <li
                key={option}
                className="listitem"
                onClick={() => {
                  onChange(option);
                  setIsOpen(false);
                }}
              >
                {option}
              </li>
            ))}
          </ul>
        )}
      </div>
    </StyledWrapper>
  );
};

const StyledWrapper = styled.div<{ width: string }>`
  .brutalist-container {
    position: relative;
    width: ${({ width }) => width};
    font-family: monospace;
  }

  .brutalist-input {
    width: 100%;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    color: #000;
    background-color: #fff;
    border: 4px solid #000;
    position: relative;
    border-radius: 0;
    outline: none;
    transition: all 0.3s ease;
    box-shadow: 5px 5px 0 #000, 10px 10px 0 #4a90e2;
    text-align: left;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
  }

  .arrow {
    font-size: 14px;
    margin-left: 10px;
  }

  .brutalist-label {
    position: absolute;
    left: -3px;
    top: -35px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    background-color: #000;
    padding: 5px 10px;
    transform: rotate(-1deg);
    z-index: 1;
    transition: all 0.3s ease;
  }

  .trigger:focus + .brutalist-label {
    background-color: #4a90e2;
  }

  .list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    border: 4px solid #000;
    border-top: none;
    background: #fff;
    z-index: 10;
    list-style: none;
    padding: 0;
    margin: 0;
    box-shadow: 5px 5px 0 #000, 10px 10px 0 #4a90e2;
    animation: dropdown 0.2s ease-out;
  }

  .listitem {
    padding: 12px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s;
  }

  .listitem:hover {
    background-color: #4a90e2;
    color: #fff;
  }

  @keyframes dropdown {
    from {
      transform: translateY(-10px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
`;

export default Accordeon;