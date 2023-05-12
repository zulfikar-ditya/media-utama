import Cookies from 'js-cookie';
import { Encrypt, token_cookie_name, useForm } from '../helpers';
import { ButtonComponent, InputComponent } from '../components/base.components';
import { faKey, faUserTag } from '@fortawesome/free-solid-svg-icons';
import Link from 'next/link';
import { Router } from 'next/router';

export default function register() {
  const onSuccess = (data) => {
    Cookies.set(
      token_cookie_name,
      Encrypt(data.token),
      { expires: 365 },
      { secure: true }
    );

    Router.push('/');
  };

  const [{ formControl, submit, loading }] = useForm(
    {
      path: 'register',
    },
    false,
    onSuccess
  );

  return (
    <>
      <div className="h-screen flex justify-center items-center">
        <div className="w-[400px] bg-white rounded-2xl shadow-lg py-12 px-8">
          <form className="flex flex-col items-center" onSubmit={submit}>
            <h1 className="text-3xl text-primary font-bold">Toko Apik</h1>

            <h2 className="my-12 text-3xl font-semibold">Register</h2>

            <div className="w-full flex flex-col gap-y-5">
              <InputComponent
                rightIcon={faUserTag}
                name="username"
                label="Username"
                size="lg"
                placeholder="Masukkan username..."
                {...formControl('username')}
              />

              <InputComponent
                rightIcon={faKey}
                type="password"
                name="password"
                label="Password"
                placeholder="Masukkan password..."
                size="lg"
                {...formControl('password')}
              />
              <InputComponent
                rightIcon={faKey}
                type="password"
                name="password_confirmation"
                label="Password"
                placeholder="Masukkan konfirmasi password..."
                size="lg"
                {...formControl('password_confirmation')}
              />
            </div>

            <div className="mt-4 text-left w-full text-md">
              <Link href="/login" className="text-primary">
                Login
              </Link>
            </div>

            <div className="w-full px-8 mt-10 mb-4">
              <ButtonComponent
                size="lg"
                type="submit"
                loading={loading}
                label="Masuk Sekarang"
                block
                rounded
              />
            </div>
          </form>
        </div>
      </div>
    </>
  );
}
