import { Link } from "react-router";
import SplitText from "../text/SplitText";

interface BreadcrumbProps {
  pageTitle: string;
  pageBack: string;
  routeBack: string;
}

const PageBreadcrumb: React.FC<BreadcrumbProps> = ({ pageTitle, pageBack, routeBack }: BreadcrumbProps) => {
  return (
    <div>
      <div className="text-xl text-semibold text-center text-black dark:text-white">
        <SplitText
          text={pageTitle}
          className="text-2xl font-semibold text-center"
          delay={100}
          duration={0.6}
          ease="power3.out"
          splitType="chars"
          from={{ opacity: 0, y: 40 }}
          to={{ opacity: 1, y: 0 }}
          threshold={0.1}
          rootMargin="-100px"
          textAlign="center"
        />
      </div>
      <div className="flex flex-wrap items-center justify-between gap-3 mb-6">

        <nav>
          <ol className="flex items-center gap-1.5">
            <li>
              <Link
                className="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                to={`/${routeBack}`}
              >
                {pageBack}
                <svg
                  className="stroke-current"
                  width="17"
                  height="16"
                  viewBox="0 0 17 16"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                    stroke=""
                    strokeWidth="1.2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  />
                </svg>
              </Link>
            </li>
            <li className="text-sm text-gray-700 dark:text-gray-300">
              {pageTitle}
            </li>
          </ol>
        </nav>
      </div>
    </div>
  );
};

export default PageBreadcrumb;
