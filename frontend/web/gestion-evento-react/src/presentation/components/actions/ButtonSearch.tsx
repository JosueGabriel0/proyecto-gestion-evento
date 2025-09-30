import styled from 'styled-components';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faMagnifyingGlass } from "@fortawesome/free-solid-svg-icons";

const ButtonSearch = () => {
  return (
    <StyledWrapper>
      <button><span className="text"><FontAwesomeIcon icon={faMagnifyingGlass} /></span><span><FontAwesomeIcon icon={faMagnifyingGlass} style={{color: "#ffffff",}} /></span></button>
    </StyledWrapper>
  );
}

const StyledWrapper = styled.div`
  button {
  border-radius: 20px;
   position: relative;
   overflow: hidden;
   border: 2px solid #18181a;
   color: #18181a;
   display: inline-block;
   font-size: 15px;
   line-height: 15px;
   padding: 18px 18px 17px;
   text-decoration: none;
   cursor: pointer;
   background: #dedede;
   user-select: none;
   -webkit-user-select: none;
   touch-action: manipulation;
  }

  button span:first-child {
   position: relative;
   transition: color 600ms cubic-bezier(0.48, 0, 0.12, 1);
   z-index: 10;
  }

  button span:last-child {
   color: white;
   display: block;
   position: absolute;
   bottom: 0;
   transition: all 500ms cubic-bezier(0.48, 0, 0.12, 1);
   z-index: 100;
   opacity: 0;
   top: 50%;
   left: 50%;
   transform: translateY(225%) translateX(-50%);
   height: 14px;
   line-height: 13px;
  }

  button:after {
   content: "";
   position: absolute;
   bottom: -50%;
   left: 0;
   width: 100%;
   height: 100%;
   background-color: black;
   transform-origin: bottom center;
   transition: transform 600ms cubic-bezier(0.48, 0, 0.12, 1);
   transform: skewY(9.3deg) scaleY(0);
   z-index: 50;
  }

  button:hover:after {
   transform-origin: bottom center;
   transform: skewY(9.3deg) scaleY(2);
  }

  button:hover span:last-child {
   transform: translateX(-50%) translateY(-50%);
   opacity: 1;
   transition: all 900ms cubic-bezier(0.48, 0, 0.12, 1);
  }`;

export default ButtonSearch;