import AddButton from "../actions/AddButton";
import ButtonSearch from "../actions/ButtonSearch";
import ElectricBorder from "../actions/ElectricBorder";
import ExcelButton from "../actions/ExcelButton";
import InputSearch from "../actions/InputSearch";
import PdfButton from "../actions/PdfButton";
import SplitText from "../text/SplitText";
// 🔹 IMPORTA el componente de filtro que desees usar
import FilterButton from "../../components/actions/FilterButton"; // por ejemplo, si existe

interface ComponentCardProps {
  title: string;
  children: React.ReactNode;
  className?: string;
  desc?: string;
  placeHolder: string;
  onSearch?: (term: string) => void;
  onAdd?: () => void;
  filter?: boolean; // 👈 esta prop ya existe
  filterPlaceHolder?: string;
  filterOnSelect?: (value: string) => void;
  filterOptions?: string[]; // 🔹 lista de opciones a mostrar en el desplegable
  defaultValue?: string
}

const ComponentCard: React.FC<ComponentCardProps> = ({
  title,
  children,
  className = "",
  desc = "",
  placeHolder = "",
  onSearch,
  onAdd,
  filter = false, // 👈 valor por defecto
  filterPlaceHolder = "",
  filterOnSelect,
  filterOptions,
  defaultValue
}) => {
  return (
    <div
      className={`rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] ${className}`}
    >
      {/* Card Header */}
      <div className="px-6 py-5 flex flex-wrap items-center justify-between gap-3">
        <h3 className="text-base font-medium text-gray-800 dark:text-white/90">
          {
            filter ? <div><FilterButton placeHolder={filterPlaceHolder} onSelect={filterOnSelect} options={filterOptions} defaultValue={defaultValue} /></div>
              : <SplitText
                text={`${title}`}
                className="text-xl font-semibold text-center"
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
          }

        </h3>

        <div className="flex items-center gap-4">
          <InputSearch onSearch={onSearch} placeHolder={placeHolder} />
          <ButtonSearch />

          <PdfButton />
          <ExcelButton />
          <div className="w-10 mr-12">
            <ElectricBorder
              color="#7df9ff"
              speed={1}
              chaos={0.5}
              thickness={10}
              style={{ borderRadius: 20 }}
            >
              <AddButton onClick={onAdd} />
            </ElectricBorder>
          </div>
        </div>

        {desc && (
          <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">{desc}</p>
        )}
      </div>

      {/* Card Body */}
      <div className="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
        <div className="space-y-6">{children}</div>
      </div>
    </div >
  );
};

export default ComponentCard;