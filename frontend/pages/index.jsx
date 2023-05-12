import { get, useGet } from '../helpers/api.helpers';

export async function getServerSideProps(context) {
  return {
    props: {}, // will be passed to the page component as props
  };
}

export default function Index(props) {
  get({ path: 'user' });

  return (
    <>
      <h1>Hello World</h1>
    </>
  );
}
